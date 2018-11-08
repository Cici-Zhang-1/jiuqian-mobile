<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月4日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_board extends MY_Controller{
    private $__Search = array(
        'order_id' => ZERO,
        'product_id' => ZERO
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Order_product_board Start !');
        $this->load->model('order/order_product_board_model');
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['order_id'])) {
            $OrderId = $this->input->get('v', true);
            $OrderId = intval($OrderId);
            if (!empty($OrderId)) {
                $this->_Search['order_id'] = $OrderId;
            }
        }

        $Data = array();
        if (!empty($this->_Search['order_id'])) {
            if(!($Data = $this->order_product_board_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            } else {
                $this->load->helper('json_helper');
                foreach ($Data['content'] as $Key => $Value) {
                    $Data['content'][$Key]['warehouse_num'] = discode_warehouse_v($Value['warehouse_num']);
                }
            }
            $Data['query']['order_id'] = $this->_Search['order_id'];
        } else {
            $this->Message = '请选择订单后查看板块信息';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    public function cabinet () {
        $this->__Search['product_id'] = CABINET;
        $this->read();
    }

    public function wardrobe () {
        $this->__Search['product_id'] = WARDROBE;
        $this->read();
    }

    public function door () {
        $this->__Search['product_id'] = DOOR;
        $this->read();
    }

    public function wood () {
        $this->__Search['product_id'] = WOOD;
        $this->read();
    }
    
/*
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
    }*/
}
