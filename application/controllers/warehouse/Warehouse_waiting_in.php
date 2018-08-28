<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Warehouse waiting in Controller
 * 等待入库订单
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Warehouse_waiting_in extends MY_Controller {
    private $__Search = array(
        'start_date' => ''
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Warehouse_waiting_in __construct Start!');
        $this->load->model('order/order_product_model');
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
        if (empty($this->_Search['start_date'])) {
            $this->_Search['start_date'] = date('Y-m-d',strtotime('-15 days'));
        }
        $Data = array();
        if(!($Data = $this->order_product_model->select_warehouse_waiting_in($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            foreach ($Data['content'] as $Key => $Value) {
                $PackDetail = json_decode($Value['pack_detail'], true);
                $Value['pack_detail'] = '';
                if (is_array($PackDetail)) {
                    foreach ($PackDetail as $IKey => $IValue) {
                        if ($IValue > 0) {
                            if ($IKey == 'thick') {
                                $Value['pack_detail'] .= ' 厚板: ' . $IValue;
                            } elseif ($IKey == 'thin') {
                                $Value['pack_detail'] .= ' 薄板: ' . $IValue;
                            } else {
                                $Value['pack_detail'] .= '  ' . $IValue . '  ';
                            }
                        }
                    }
                }
                $Data['content'][$Key] = $Value;
            }
        }
        $this->_ajax_return($Data);
    }
}
