<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月24日
 * @author Administrator
 * @version
 * @des
 * 非及时到账
 */
class Finance_received_outtime extends CWDMS_Controller{
    private $_Module = 'finance';
    private $_Controller;
    private $_Item ;
    private $_Cookie;
    private $_Faid;
    private $_CargoNo;
    private $_Type;
    private $_Did;
    private $_Dealer;
    private $_OrderNum;
    private $_Amount;
    private $_Account;
    private $_Fee;
    private $_Corresponding;
    private $_Status = FALSE;
    private $_Remark = array();
    
    private $Search = array(
        'status' => '',
        'start_date' => '',
        'end_date' => '',
        'type' => '',
        'account' => '',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('finance/finance_received_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';

        log_message('debug', 'Controller Finance/Finance_received_outtime Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view($Item, $Data);
        }
    }

    public function read(){
        $Cookie = $this->_Cookie.__FUNCTION__;
        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $Data = array();
        if(!empty($this->Search)){
            if(!!($Data = $this->finance_received_model->select_outtime($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的订单';;
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }

    public function add(){
        $Run = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $this->_Faid = $this->input->post('faid', true);
            $this->_Type = $this->input->post('type', true);
            $this->_Did = $this->input->post('did', true);
            $this->_Dealer = $this->input->post('dealer', true);
            $this->_OrderNum = $this->input->post('order_num', true);
            $this->_Amount = $this->input->post('amount', true);
            $this->_CargoNo = $this->input->post('cargo_no', true);
            $this->_Fee = $this->input->post('fee', true);
            $this->_Corresponding = $this->input->post('corresponding', true);
            $_Remark = $this->input->post('remark', true);
            $_Remark = trim($_Remark);

            if(!empty($_Remark)){
                $this->_Remark[] = $_Remark;
            }
            if(empty($this->_Did)){
                /*如果没有经销商, 则不需要对应到订单和经销商*/
                if('银行结息' == $this->_Type){
                    $Data = array(
                        'in' => $this->_Amount,
                        'in_fee' => $this->_Fee
                    );
                    $this->_Status = 2;/*直接到账的*/
                    $this->load->model('finance/account_model');
                    $this->_add_finance_received()
                    && $this->account_model->update_balance_in($Data, $this->_Faid);
                }else{
                    $this->Failue = '请选择经销商!';
                }
                
                /* $this->_edit_finance_received(); */
            }else{
                $this->_edit_dealer() 
                && $this->_set_order_num()
                && $this->_edit_order_payed() 
                && $this->_add_finance_received();
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    private function _set_order_num(){
        if(is_array($this->_OrderNum)){
            $this->_Remark = array_merge($this->_Remark, $this->_OrderNum);
        }elseif(!empty($this->_OrderNum)) {
            $this->_OrderNum = array(
                $this->_OrderNum
            );
            $this->_Remark = array_merge($this->_Remark,$this->_OrderNum);
        }
        return true;
    }
    
    /**
     * 更新经销商的账目信息
     */
    private function _edit_dealer(){
        if($this->_Did){
            $this->load->model('dealer/dealer_model');
            $Debt = $this->dealer_model->update_dealer_received($this->_Corresponding, $this->_Did);
            if(false !== $Debt ){
                $this->_Remark[] = '[截止本次付款，账户余额为 '.$Debt.' 元]';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'更新客户发货欠款失败';
                return false;
            }
        }
        return true;
    }
    
    /**
     * 更新订单收款之后的状态
     */
    private function _edit_order_payed(){
        if(!empty($this->_OrderNum)){
            $this->load->model('order/order_model');
            $this->_OrderNum = gh_escape($this->_OrderNum);
            $Return = $this->order_model->update_order_payed($this->_OrderNum, $this->_CargoNo);
            if(is_array($Return)){
                //if(!empty($this->_CargoNo)){/*物流代收必有货号*/
                $this->load->library('workflow/workflow');
                foreach ($Return as $key => $value){
                    if($this->workflow->initialize('order', $value)){
                        $this->workflow->payed();/*物流代收状态变为已发货状态*/
                    }else{
                        $this->Failue .= $this->workflow->get_faile();
                        break;
                    }
                }
                //}
            }elseif (false == $Return){
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'挂账到订单失败';
                return false;
            }
        }
        return true;
    }
    /**
     * 添加财务收款信息
     */
    private function _add_finance_received(){
        $Data = array(
            'faid' => $this->_Faid,
            'cargo_no' => $this->_CargoNo,
            'amount' => $this->_Amount,
            'fee' => $this->_Fee,
            'type' => $this->_Type,
            'did' => $this->_Did,
            'dealer' => $this->_Dealer,
            'corresponding' => $this->_Corresponding,
            'bank_date' => date('Y-m-d'),
            'status' => (FALSE !== $this->_Status) ? $this->_Status:1
        );
        if(is_array($this->_Remark)){
            $Data['remark'] = implode(',', $this->_Remark);
        }else{
            $Data['remark'] = $this->_Remark;
        }
        $Data = gh_escape($Data);
        $this->load->model('finance/finance_received_model');
        if(!!($this->finance_received_model->insert_outtime($Data))){
            return true;
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'进账登记失败!';
            return false;
        }
    }
    
    /**
     * 已到账
     */
    public function edit(){
        $Run = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $this->_Fee = $this->input->post('fee');
            $Selected = $this->input->post('selected');
            if(!!($FinanceReceived = $this->finance_received_model->is_valid_finance_received($Selected))){
                $this->_Faid = $FinanceReceived['faid'];
                $Data = array(
                    'in' => $FinanceReceived['amount'],
                    'in_fee' => empty($this->_Fee)?$FinanceReceived['fee']:$this->_Fee
                );
                $this->load->model('finance/account_model');
                if($this->account_model->update_balance_in($Data, $this->_Faid)
                    && $this->finance_received_model->update_outtime(array('fee' => $Data['in_fee']), $Selected)){/*非及时到账，则跟新账户*/
                    if(!empty($this->_CargoNo)){
                        $this->load->model('order/order_model');
                        $Return = $this->order_model->select_order_num_by_cargo_no($this->_CargoNo);
                        if(is_array($Return)){
                            $this->load->library('workflow/workflow');
                            foreach ($Return as $key => $value){
                                if($this->workflow->initialize('order', $value['oid'])){
                                    $this->workflow->outted();/*物流代收状态变为已发货状态*/
                                }else{
                                    $this->Failue .= $this->workflow->get_faile();
                                    break;
                                }
                            }
                        }
                    }
                    $this->Success .= '进账已到账成功, 刷新后生效!';
                }else{
                    $this->Failue = '进账已到账失败!';
                }
            }else{
                $this->Failue = '进账不存在!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    

    /**
     * 废止
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if(!!($FinanceReceived = $this->finance_received_model->is_valid_finance_received($Where))){
                $this->_Did = $FinanceReceived['did'];
                $this->_Corresponding = -1*$FinanceReceived['corresponding'];
                $this->_Amount = -1*$FinanceReceived['amount'];
                $this->_CargoNo = $FinanceReceived['cargo_no'];
                $this->_edit_dealer() 
                && $this->_edit_order_repay()
                && $this->finance_received_model->delete_outtime($Where);
                $this->Success .= '进账删除改成功, 刷新后生效!';
            }elseif (!!($FinanceReceived = $this->finance_received_model->is_valid_finance_received($Where, array(2)))){
            	$this->_Did = $FinanceReceived['did'];
            	$this->_Corresponding = -1*$FinanceReceived['corresponding'];
            	$this->_Amount = -1*$FinanceReceived['amount'];
            	$this->_Fee = -1*$FinanceReceived['fee'];
            	$this->_Account = $FinanceReceived['faid'];
            	$this->_CargoNo = $FinanceReceived['cargo_no'];
            	$this->_edit_dealer()
            	&& $this->_edit_account()
            	&& $this->finance_received_model->delete_outtime($Where);
            	$this->Success .= '进账删除改成功, 刷新后生效!';
            }else{
                $this->Failue = '进账已经已到账, 不可删除!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    private function _edit_order_repay(){
        if(!empty($this->_CargoNo)){
            $this->load->model('order/order_model');
            $Return = $this->order_model->update_order_repay($this->_CargoNo);
            if(is_array($Return)){
                $this->load->library('workflow/workflow');
                foreach ($Return as $key => $value){
                    if($this->workflow->initialize('order', $value)){
                        $this->workflow->repay();/*物流代收状态变为已发货状态*/
                    }else{
                        $this->Failue .= $this->workflow->get_faile();
                        break;
                    }
                }
            }elseif (false == $Return){
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除非及时到账失败!';
                return false;
            }
        }
        return true;
    }
    
    private function _edit_account(){
    	$this->load->model('finance/account_model');
    	$Data = array(
    			'in' => $this->_Amount,
    			'in_fee' => $this->_Fee
    	);
    	$this->account_model->update_balance_in($Data, $this->_Account);
    	return true;
    }
}