<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月4日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_board extends CWDMS_Controller{
    private $Module = 'order';

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Order_product_board Start !');
        $this->load->model('order/order_product_board_model');
    }

    public function read(){
        $Oid = $this->input->get('id', true);
        $Product = $this->input->get('product', true);
        $Product = trim($Product);
        $Oid = intval(trim($Oid));
        $Data = array();
        if($Oid && $Product != false){
            $Cache = $Oid.'_'.$Product.'_order_order_product_board';
            $this->e_cache->open_cache();
            $Return = array();
            if(!($Return = $this->cache->get($Cache))){
                if(!!($Query = $this->order_product_board_model->select_order_product_board($Oid, $Product))){
                    $this->config->load('dbview/order');
                    $Dbview = $this->config->item('order/order_product_board/read');
                    foreach ($Query as $key=>$value){
                        foreach ($Dbview as $ikey=>$ivalue){
                            $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                        }
                    }
                    $this->cache->save($Cache, $Return, HOURS);
                }else{
                    $this->Failue .= '该订单暂时没有'.$Product.'柜体信息';
                }
            }
            $Data['content'] = $Return;
            unset($Return);
        }
        $this->_return($Data);
    }
    

    public function edit(){
        $Selected = $this->input->post('selected', true);
        $Selected = explode(',', $Selected);
        $Board = $this->input->post('board', true);
        $Board = trim($Board);
        $this->load->model('order/order_product_board_plate_model');
        if(is_array($Selected && count($Selected) > 0 && !empty($Board))){
            !!($Opid = $this->order_product_board_plate_model->select_order_product_board_opid(gh_mysql_string($Selected))) &&
            !!($Opbids = $this->order_product_board_model->insert_order_product_board_from_plate($Opid, gh_mysql_string($Board)))
            && !!($this->order_product_board_plate_model->update_order_product_board_plate_board($Opbids, $Selected));
            $this->Success .= '订单信息修改成功, 刷新后生效!';
            $this->load->helper('file');
            delete_cache_files('(.*order.*)');
        }else{
            $this->Failue .= '请选择需要修改的板材!';
        }
        $this->_return();
    }
}