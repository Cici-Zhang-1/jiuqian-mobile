<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Remark list Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Remark_list extends MY_Controller {
    private $__Search = array(
        'status' => NO,
        'order_id' => ZERO,
        'product_id' => array(CABINET, WARDROBE)
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller chart/Remark_list __construct Start!');
        $this->load->model('order/remark_list_model');
    }

    /**
    *
    * @return void
    */
    public function index() {
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->remark_list_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }


    public function prints () {
        $OrderId = $this->input->get('v', true);
        if (!is_array($OrderId)) {
            $OrderId = explode(',', $OrderId);
        }
        foreach ($OrderId as $Key => $Value) {
            $Value = intval(trim($Value));
            if (empty($Value)) {
                unset($OrderId[$Key]);
            } else {
                $OrderId[$Key] = $Value;
            }
        }
        if (empty($OrderId)) {
            show_error('请选择需要打印的订单');
        } else {
            $this->__Search['order_id'] = $OrderId;
            $Data = array();
            $this->_Search = array_merge($this->_Search, $this->__Search);
            $this->get_page_search();
            $Order = $this->_read_order();
            $OrderProductBoard = $this->_read_order_product_board();
            $this->_add_remark_list();
            if (empty($Order)) {
                show_error('您选择的订单不存在');
            } else {
                if (empty($OrderProductBoard)) {
                    show_error('您选择的订单不包含橱柜和衣柜产品');
                } else {
                    foreach ($Order as $Key => $Value) {
                        if (isset($OrderProductBoard[$Value['v']])) {
                            $Order[$Key]['order_product_board'] = $OrderProductBoard[$Value['v']];
                        } else {
                            unset($Order[$Key]);
                        }
                    }
                    if (empty($Order)) {
                        show_error('您选择的订单不包含橱柜和衣柜产品');
                    } else {
                        $Data['order'] = array_values($Order);
                        $this->load->view('header2');
                        $this->load->view($this->_Item . __FUNCTION__, $Data);
                    }
                }
            }
        }
    }

    private function _add_remark_list () {
        $this->load->model('order/remark_list_model');
        return $this->remark_list_model->insert_batch_update($this->_Search['order_id']);
    }
    private function _read_order () {
        $this->load->model('order/order_model');
        $Data = array();
        if (!!($Query = $this->order_model->select_details($this->_Search))) {
            $Data = $Query['content'];
        }
        return $Data;
    }

    private function _read_order_product_board () {
        $this->load->model('order/order_product_board_model');
        $Data = array();
        if (!!($Query = $this->order_product_board_model->select($this->_Search))) {
            foreach ($Query['content'] as $Key => $Value) {
                if (!isset($Data[$Value['order_id']])) {
                    $Data[$Value['order_id']] = array();
                }
                array_push($Data[$Value['order_id']], $Value);
            }
        }
        return $Data;
    }
}
