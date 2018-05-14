<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月25日
 * @author Zhangcc
 * @version
 * @des
 * 流程节点记录
 */

class Workflow_msg extends MY_Controller{
    private $_Module = 'data';
    private $_Controller;
    private $_Item;
    private $_Cookie;
    
    private $_Id;
    
    public function __construct(){
        parent::__construct();
        $this->load->model('data/workflow_msg_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller Workflow/Workflow_msg Start !');
    }
    
    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $this->data['action'] = site_url($Item);
            $this->load->view($Item, $this->data);
        }
    }
    
    private function _read(){
        if(empty($this->_Id)){
            $Id = $this->input->get('id');
            $this->_Id = intval(trim($Id));
        }
        if($this->_Id){
            $this->load->model('order/order_product_model');
            $Data = array();
            $OrderProduct = array();
            $Tmp1 = array();
            $Tmp2 = array();
            $Tmp3 = array();
            $Tmp4 = array();
            if(!!($Query = $this->order_product_model->select_by_oid($this->_Id))){ /*订单产品类*/
                foreach ($Query as $key => $value){
                    $OrderProduct[] = $value['opid'];
                }
                if(!!($Query = $this->workflow_msg_model->select_by_opids($OrderProduct))){
                    foreach ($Query as $key => $value){
                        $Tmp1[$value['wmid']] = $value;
                    }
                }
                
                $this->load->model('order/order_product_classify_model');
                if(!!($Query = $this->order_product_classify_model->select_opcids_by_opids($OrderProduct))){
                    foreach ($Query as $key => $value){
                        $OrderProductClassify[] = $value['opcid'];
                    }
                    if(!!($Query = $this->workflow_msg_model->select_by_opcids($OrderProductClassify))){
                        foreach ($Query as $key => $value){
                            $Tmp4[$value['wmid']] = $value;
                        }
                    }
                }
            }
            if(!!($Query = $this->workflow_msg_model->select_by_oid($this->_Id))){
                foreach ($Query as $key => $value){
                    $Tmp2[$value['wmid']] = $value;
                }
                
                $Tmp3 = $Tmp1 + $Tmp2 + $Tmp4;
                krsort($Tmp3);
                
                $Data['Msg'] = $Tmp3;
                unset($Tmp1,$Tmp2,$Tmp3,$Tmp4);
            }
            $this->load->view($this->_Item.__FUNCTION__, $Data);
        }else{
            show_error('您要访问的订单不存在!');
        }
    }
}
