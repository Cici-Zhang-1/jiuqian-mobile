<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Zhangcc
 * @version
 * @des
 * 登记货号
 */

class Cargo_no extends CWDMS_Controller{
    private $_Module = 'order';
    private $_Item ;
    private $_Cookie ;
    private $_Id;

    private $Search = array(
        'status' => '17,18,19,20,21'
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('stock/stock_outted_model');
        $this->_Item = $this->_Module.'/'.strtolower(__CLASS__).'/';
        $this->_Cookie = $this->_Module.'_'.strtolower(__CLASS__).'_';
        
        log_message('debug', 'Controller Order/Cargo_no Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, false);
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view($Item, $Data);
        }
    }

    private function _edit(){
        $Item = $this->_Item.__FUNCTION__;
        $this->_Id = $this->input->get('id', true);
        $this->_Id = intval(trim($this->_Id));
        if(!$this->_Id){
            $this->_Id = $this->input->cookie('soid');
            $this->_Id = intval(trim($this->_Id));
        }
        $Data = array();
        if($this->_Id > 0){
            $this->load->model('order/order_model');
            if(!!($Query = $this->order_model->select_by_soid($this->_Id, $this->Search['status']))){
                foreach ($Query as $key => $value){
                    $Detail = array(
                        'W' => 0,
                        'Y' => 0,
                        'M' => 0,
                        'K' => 0,
                        'P' => 0,
                        'G' => 0
                    );
                    $Tmp = json_decode($value['pack_detail'], true);
                    if(is_array($Tmp)){
                        $Detail = array_merge($Detail, $Tmp);
                    }
                    $Detail['order_num'] = $value['order_num'];
                    $Detail['oid'] = $value['oid'];
                    if('物流代收' == $value['payed']){
                        $Payed = $value['sum'];
                    }else{
                        $Payed = 0;
                    }
                    if(empty($Data['Order'][$value['did']])){
                        $Data['Order'][$value['did']] = array(
                            'soid' => $value['soid'],
                            'did' => $value['did'],
                            'dealer' => $value['dealer'],
                            'delivery_address' => $value['delivery_area'].$value['delivery_address'],
                            'delivery_linker' => $value['delivery_linker'],
                            'delivery_phone' => $value['delivery_phone'],
                            'logistics' => $value['logistics'],
                            'amount' => $value['pack'],
                            'payed' => $Payed,
                            'detail' => array($Detail),
                            'cargo_no' => $value['cargo_no']
                        );
                    }else{
                        $Data['Order'][$value['did']]['amount'] += $value['pack'];
                        $Data['Order'][$value['did']]['payed'] += $Payed;
                        array_push($Data['Order'][$value['did']]['detail'], $Detail);
                    }
                }
                unset($Query);
            }else{
                $Data['Error'] = '您要登记货号的订单不存在!';
            }
        }else{
            $Data['Error'] = '请先选择要登记货号的车次!';
        }
        $this->load->view($Item, $Data);
    }
    /**
     * 编辑填写发货货号
     */
    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Oid = $this->input->post('oid', true);
            $CargoNo = $this->input->post('cargo_no', true);
            $Set = array();
            $Workflow = array();
            foreach ($Oid as $key=>$value){
                foreach ($value as $ikey => $ivalue){
                    $ivalue = intval(trim($ivalue));
                    if($ivalue > 0){
                        $Set[] = array(
                            'oid' => $ivalue,
                            'cargo_no' => $CargoNo[$key]
                        );
                        $Workflow[] = $ivalue;
                    }
                }
            }
            if(!empty($Set)){
                $Set = gh_escape($Set);
                $this->load->model('order/order_model');
                $this->order_model->update_batch($Set);
                $this->order_model->remove_cache($this->_Module);
                unset($Set);
                $this->load->helper('cookie');
                delete_cookie('soid');
                $this->load->library('workflow/workflow');
                foreach ($Workflow as $key => $value){ /*最后节点分为已发货和物流代收, 已发货在此转为已出厂, 物流代收在登帐时转未已出厂*/
                    if($this->workflow->initialize('order', $value)){
                        $this->workflow->outted();
                        $this->Success = '货号登记成功';
                    }else{
                        $this->Failue .= $this->workflow->get_failue();
                    }
                }
            }else{
                $this->Failue = '没有需要填写货号的订单!';
            }
        }else{
            $this->Failue = validation_errors();
        }
        $this->_return();
    }
}