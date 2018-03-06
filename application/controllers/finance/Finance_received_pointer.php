<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月9日
 * @author Zhangcc
 * @version
 * @des
 * 财务收款指派
 */
class Finance_received_pointer extends CWDMS_Controller{
    private $_Module = 'finance';
    private $_Controller;
    private $_Item ;
    private $_Frid;
    private $_Type;
    private $_Did;
    private $_Dealer;
    private $_OrderNum;
    private $_Amount;
    private $_Corresponding;
    private $_Remark = array();
    
    public function __construct(){
        parent::__construct();
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        
        log_message('debug', 'Controller Finance/Finance_received_pointer Start!');
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
    
    private function _add(){
        $Id = $this->input->get('id', true);
        $Id = intval(trim($Id));
        if($Id > 0){
            $this->load->model('finance/finance_received_model');
            if(!!($Data = $this->finance_received_model->is_valid_finance_received($Id))){
                if(!empty($Data['cargo_no'])){
                    $this->load->model('order/order_model');
                    $Data['order'] = $this->order_model->select_order_num_by_cargo_no($Data['cargo_no'], date('Y-m-d', time() - MONTHS));
                }
                $this->load->view($this->_Item.__FUNCTION__, $Data);
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要认领的登帐不存在,或者已被认领';
                $this->close_tab($this->Failue);
            }
        }else{
            $this->close_tab('您访问的内容不存在!');
        }
    }

    public function add(){
        $Run = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $this->_Frid = $this->input->post('frid', true);
            $this->_Type = $this->input->post('type', true);
            $this->_Did = $this->input->post('did', true);
            $this->_Dealer = $this->input->post('dealer', true);
            $this->_OrderNum = $this->input->post('order_num', true);
            $this->_Amount = $this->input->post('amount', true);
            $this->_Corresponding = $this->input->post('corresponding', true);
            $_Remark = $this->input->post('remark', true);
            $_Remark = trim($_Remark);
            if(!empty($_Remark)){
                $this->_Remark[] = $_Remark;
            }
            if(empty($this->_Did)){
                /*如果没有经销商, 则不需要对应到订单和经销商*/
                $this->_edit_finance_received();
            }else{
                $this->_edit_dealer() && $this->_edit_order_payed() && $this->_edit_finance_received();
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->Location = 'close';
        $this->_return();
    }
    
    /**
     * 更新订单收款之后的状态
     */
    private function _edit_order_payed(){
        if(is_array($this->_OrderNum)){
            $this->_Remark = array_merge($this->_Remark, $this->_OrderNum);
        }elseif(!empty($this->_OrderNum)) {
            $this->_OrderNum = array(
                $this->_OrderNum
            );
            $this->_Remark = array_merge($this->_Remark,$this->_OrderNum);
        }
        if(!empty($this->_OrderNum)){
            $this->load->model('order/order_model');
            $this->_OrderNum = gh_escape($this->_OrderNum);
            $Return = $this->order_model->update_order_payed($this->_OrderNum);
            if(is_array($Return)){
                /*之前未支付的订单，支付认领后订单状态可能会改变*/
                $this->load->library('workflow/workflow');
                foreach ($Return as $key => $value){
                    if($this->workflow->initialize('order', $value)){
                        $this->workflow->payed();
                    }else{
                        $this->Failue .= $this->workflow->get_faile();
                        break;
                    }
                }
            }elseif (false == $Return){
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'挂账到订单失败';
                return false;
            }
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
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'挂账到客户失败';
                return false;
            }
        }
        return true;
    }
    
    /**
     * 更新财务收款信息
     */
    private function _edit_finance_received(){
        $Data = array(
            'type' => $this->_Type,
            'did' => $this->_Did,
            'dealer' => $this->_Dealer,
            'corresponding' => $this->_Corresponding
        );
        if(is_array($this->_Remark)){
            $Data['remark'] = implode(',', $this->_Remark);
        }else{
            $Data['remark'] = $this->_Remark;
        }
        $Data = gh_escape($Data);
        $this->load->model('finance/finance_received_model');
        if(!!($this->finance_received_model->update_finance_received_pointer($Data, $this->_Frid))){
            return true;
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'挂账失败';
            return false;
        }
    }
}