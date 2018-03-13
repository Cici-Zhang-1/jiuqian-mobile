<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月23日
 * @author Zhangcc
 * @version
 * @des
 * 等待报价
 */
class Wait_quote extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item ;
    private $_Cookie;
    private $Count;
    private $InsertId;
    private $Search = array(
        'status' => '7',
        'keyword' => ''
    );
    public function __construct(){
        log_message('debug', 'Controller Order/Wait_quote Start!');
        parent::__construct();
        $this->load->model('order/order_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
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
            if(!!($Data = $this->order_model->select($this->Search, $this->_Item.__FUNCTION__))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要报价的订单';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }

    
    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected', true);
            if(!!($Query = $this->order_model->is_quotable($Selected))){
                $Order = array();
                $MoneyProduce = array();
                $WaitAsure = array();
                foreach ($Query as $key => $value){
                    $Order[$value['oid']] = array(
                        'oid' => $value['oid'],
                        'payed_datetime' => null
                    );
                    
                    if(!empty($value['payed_datetime'])){
                        $Order[$value['oid']]['payed_datetime'] = $value['payed_datetime'];
                        $WaitAsure[] = $value['oid'];
                    }elseif(0 == $value['sum']
                        || ($value['balance'] - $value['debt2'] - $value['debt1'] - $value['sum']) >= 0){
                        /*如果账户余额能购支付当前订单, 则下已状态为等待生产*/
                        $Order[$value['oid']]['payed_datetime'] = date('Y-m-d H:i:s');
                        $WaitAsure[] = $value['oid'];
                    }else{
                        if('款到生产' == $value['payterms']){
                            $MoneyProduce[] = $value['oid'];
                        }else{
                            $WaitAsure[] = $value['oid'];
                        }
                    }
                    
                    /*更新客户等待生产的账目*/
                    if(isset($Dealer[$value['did']])){
                        $Dealer[$value['did']]['debt1'] += $value['sum'];
                    }else{
                        $Dealer[$value['did']] = array(
                            'did' => $value['did'],
                            'debt1' => $value['debt1'] + $value['sum']
                        );
                    }
                }
                
                /* 对于当前账目余额购支付订单的则默认自动支付 */
                $this->order_model->update_batch($Order);
                
                /* 更新客户的等待生产的欠款 */
                if(!empty($Dealer)){
                    $this->load->model('dealer/dealer_model');
                    $this->dealer_model->update_batch($Dealer);
                }
                
                $this->load->library('workflow/workflow');
                if(!empty($MoneyProduce)){
                    /*款到生产*/
                    if($this->workflow->initialize('order', $MoneyProduce)){
                        $this->workflow->quote('money_produce');
                    }
                }
                if(!empty($WaitAsure)){
                    /**
                     * 等待生产
                     */
                    if($this->workflow->initialize('order', $WaitAsure)){
                        $this->workflow->quote('wait_asure');
                    }
                }
                
                $this->Failue = $this->workflow->get_failue();
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要报价确认的订单';
            }
        }else{
            $this->Failue = validation_errors();
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
            if($Where !== false && is_array($Where) && count($Where) > 0){
                $this->workflow->action($Where, 'order', 2);
                $this->Success = '作废成功';
            }else{
                $this->Failue .= '没有可作废项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
