<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月21日
 * @author Zhangcc
 * @version
 * @des
 * 已出厂
 */
class Stock_outted extends CWDMS_Controller{
    private $_Module = 'stock';
    private $_Item ;
    private $_Cookie ;
    private $Count;
    private $_Id;
    
    private $Search = array(
        'status' => '17,18,19,20',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('stock/stock_outted_model');
        $this->_Item = $this->_Module.'/'.strtolower(__CLASS__).'/';
        $this->_Cookie = $this->_Module.'_'.strtolower(__CLASS__).'_';
        
        log_message('debug', 'Controller Order/Stock_outted Start!');
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
            if(!!($Data = $this->stock_outted_model->select($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有已出厂的订单';
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }
    
    public function redelivery(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected', true);
            $this->load->model('order/order_model');
            if(!!($this->stock_outted_model->delete($Selected))){
                if(!!($Query = $this->order_model->is_redeliveriable($Selected, $this->Search['status'], TRUE))){
                    $Order = array();
                    $Dealer = array();
                    foreach ($Query as $key=>$value){
                        if(empty($Dealer[$value['did']])){
                            $Dealer[$value['did']] = $value['sum'];
                        }else{
                            $Dealer[$value['did']] += $value['sum'];
                        }
                        $Order[] = $value['oid'];
                    }
                    if(!empty($Dealer)){
                        $this->load->model('dealer/dealer_model');
                        $this->dealer_model->update_dealer_redelivery($Dealer);
                    }
                    unset($Query);
                
                    $Set = array(
                        'end_datetime' => null,
                        'stock_outted_id' => 0
                    );
                    if(!!($this->order_model->update_order($Set, $Order))){
                        $this->load->library('workflow/workflow');
                        if(!!($this->workflow->initialize('order', $Order))){
                            $this->workflow->redelivery();
                            $this->Success = '重新发货成功';
                        }else{
                            $this->Failue = $this->workflow->get_failue();
                        }
                    }else{
                        $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'重新发货失败';
                    }
                }
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'重新发货失败';
            }
            
        }else{
            $this->Failue = validation_errors();
        }
        $this->_return();
    }
}