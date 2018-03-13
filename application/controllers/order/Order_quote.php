<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月25日
 * @author Zhangcc
 * @version
 * @des
 * 生成报价单
 */
class Order_quote extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $_Type;
    private $_Id;

    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';

        log_message('debug', 'Controller Order/Order_quote Start !');
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
        if(empty($this->_Type)){
            $this->_Type = $this->uri->segment(5, false);
            $this->_Type = trim($this->_Type);
        }
        if(empty($this->_Id)){
            $Id = $this->input->get('id');
            $this->_Id = intval(trim($Id));
        }
        if($this->_Id && $this->_Type){
            $Method = __FUNCTION__.'_'.$this->_Type;
            if(method_exists(__CLASS__, $Method)){
                if(!!($Data['Info'] = $this->$Method())){
                    $Data['Detail'] = $this->_read_detail();
                    $this->load->view('header2');
                    $this->load->view($this->_Item.__FUNCTION__, $Data);
                }else{
                    show_error('您要访问的内容不存在');
                }
            }else{
                show_error('您要访问的内容不存在');
            }
        }else{
            show_error('您要访问的订单不存在!');
        }
    }

    /**
     * 通过订单Id号获取信息
     */
    private function _read_order(){
        $this->load->model('order/order_model');
        if(!!($Return = $this->order_model->select_order_detail($this->_Id))){
            return $Return;
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要访问的订单不存在..';
            return false;
        }
    }
    /**
     * 通过订单产品Id号获取信息
     */
    private function _read_order_product(){
        $this->load->model('order/order_product_model');
        if(!($Return = $this->order_product_model->select_order_detail_by_opid($this->_Id))){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您要访问的订单不存在..';
            return false;
        }else{
            $this->_Id = $Return['oid'];
            return $Return;
        }
    }

    private function _read_detail(){
        $this->load->library('d_order');
        $this->load->model('order/order_product_model');
        if(!!($OrderProduct = $this->order_product_model->select_by_oid($this->_Id, $this->_Item.__FUNCTION__))){
            $Sort1 = array();
            $Sort2 = array();
            foreach ($OrderProduct as $key => $value){
                if(0 == $value['parent']){
                    if(isset($Sort1[$value['opid']])){
                        $Sort1[$value['opid']] = array_merge($Sort1[$value['opid']], $value);
                    }else{
                        $Sort1[$value['opid']] = $value;
                        $Sort1[$value['opid']]['child'] = array();
                    }
                }else{
                    $Sort1[$value['parent']]['child'][] = $value;
                }
            }
            unset($OrderProduct);
            foreach ($Sort1 as $key => $value){
                $Child = $value['child'];
                unset($value['child']);
                $Sort2[] = $value;
                if(!empty($Child)){
                    $Sort2 = array_merge($Sort2, $Child);
                }
            }
            unset($Sort1);
            foreach ($Sort2 as $key => $value){
                $Sort2[$key]['detail'] = $this->d_order->read(strtolower($value['code']), $value['opid']);
                $Sort2[$key]['position'] = $this->_read_position($value['opid']);
            }
            return $Sort2;
        }else{
            return false;
        }
    }

    private function _read_position($Opid) {
        $this->load->model('position/position_order_product_model');
        $Return = false;
        if (!!($Query = $this->position_order_product_model->select_position_by_opid($Opid))) {
            $Return = $Query['name'];
        }
        return $Return;
    }
}
