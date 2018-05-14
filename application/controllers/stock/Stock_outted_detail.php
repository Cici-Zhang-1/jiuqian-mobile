<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Zhangcc
 * @version
 * @des
 * 发货详情
 */
class Stock_outted_detail extends MY_Controller{
    private $_Module = 'stock';
    private $_Item ;

    private $Search = array(
        'status' => '17,18,19,20',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->_Item = $this->_Module.'/'.strtolower(__CLASS__).'/';

        log_message('debug', 'Controller stock/Stock_outted_detail Start!');
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

    private function _read(){
        $Id = $this->input->get('id', true);
        $Id = intval(trim($Id));
        $Item = $this->_Item.__FUNCTION__;
        if($Id > 0){
            $this->load->model('order/order_model');
            $Data = array();
            if(!($Data['Detail'] = $this->order_model->select_by_soid($Id, $this->Search['status']))){
                $Data['Failue'] = '您要查看的详情不存在!';
            }else{
                $this->input->set_cookie(array('name'=>'soid', 'value' => $Id, 'expire' => DAYS));
            }
        }else{
            $Data['Failue'] = '您要查看的详情不存在!';
        }
        $this->load->view($Item, $Data);
    }

    public function redelivery(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected', true);
            $this->load->model('order/order_model');
            if(!!($this->stock_outted_model->delete($Selected))){
                if(!!($Query = $this->order_model->is_redeliveriable($Selected, $this->Search['status']))){
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
                        $this->load->model('workflow/workflow');
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
    }
}
