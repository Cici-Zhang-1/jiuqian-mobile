<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月2日
 * @author Zhangcc
 * @version
 * @des
 * 五金配件
 */
class Order_product_fitting extends MY_Controller{
    private $__Search = array(
        'order_id' => ZERO,
        'order_product_id' => ZERO
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Order_product_fitting Start !');
        $this->load->model('order/order_product_fitting_model');
    }

    public function read(){
        $this->_page_search();
        $Data = array();
        if (!empty($this->_Search['order_id']) || !empty($this->_Search['order_product_id'])) {
            if(!($Data = $this->order_product_fitting_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            }
            $Data['query']['order_id'] = $this->_Search['order_id'];
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        } else {
            $this->Message = '请选择订单或订单产品后查看配件信息';
            $this->Code = EXIT_ERROR;
        }

        $this->_ajax_return($Data);
    }

    private function _page_search () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['order_id'])) {
            $OrderId = $this->input->get('v', true);
            $this->_Search['order_id'] = intval($OrderId);
        }
        return $this->_Search;
    }

    public function prints () {
        $this->_page_search();
        $Data = array();
        if (!empty($this->_Search['order_id']) || !empty($this->_Search['order_product_id'])) {
            if(!($Data = $this->order_product_fitting_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            }
            $Data['query']['order_id'] = $this->_Search['order_id'];
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        } else {
            $this->Message = '请选择订单或订单产品后查看配件信息';
            $this->Code = EXIT_ERROR;
        }

        $this->_ajax_return($Data);
    }
}
