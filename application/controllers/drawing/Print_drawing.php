<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Print drawing Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Print_drawing extends MY_Controller {
    private $__Search = array(
        'order_product_id' => ZERO,
        'drawing_id' => ZERO
    );
    private $_V;
    private $_Type;
    private $_OrderProduct;
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller drawing/Print_drawing __construct Start!');
        $this->load->model('drawing/drawing_model');
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
        if (empty($this->_Search['drawing_id'])) {
            $this->_Search['drawing_id'] = $this->input->get('v', true);
            $this->_Search['drawing_id'] = intval($this->_Search['drawing_id']);
        }
        $Data = array();
        if(!($Data = $this->drawing_model->select($this->_Search))) {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            foreach ($Data['content'] as $Key => $Value) {
                $Value['url'] = drawing_url($Value['path']);
                $Data['content'][$Key] = $Value;
            }
        }
        if (!empty($this->_Search['order_product_id'])) {
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        }
        if (!empty($this->_Search['drawing_id'])) {
            $Data['query']['drawing_id'] = $this->_Search['drawing_id'];
        }
        $this->_ajax_return($Data);
    }

    private function _prints(){
        $this->_V = $this->input->get('v', true);
        $this->_V = intval(trim($this->_V));
        $this->_Type = $this->input->get('type', true);
        $this->_Type = intval(trim($this->_Type));
        if (empty($this->_V)) {
            show_error('请选择需要打印图纸的订单');
        } else {
            if (ZERO == $this->_Type) {
                $A = $this->_read_order_product_classify();
            } else {
                $A = $this->_read_order_product_board();
            }
            if ($A) {
                if(!!($Return['PrintDrawing'] = $this->drawing_model->select_by_order_product_id($this->_OrderProduct['order_product_id']))){
                    $this->load->view('header2');
                    $this->load->view($this->_Item.__FUNCTION__, $Return);
                } else {
                    show_error(isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有图纸可以打印');
                }
            } else {
                show_error('没有找到对应的打印订单');
            }
        }
    }

    private function _read_order_product_classify () {
        $this->load->model('order/order_product_classify_model');
        if (!!($Query = $this->order_product_classify_model->has_brothers($this->_V))) {
            $this->_OrderProduct = $Query[array_rand($Query, ONE)];
            return true;
        }
        return false;
    }

    private function _read_order_product_board () {
        $this->load->model('order/order_product_board_model');
        if (!!($Query = $this->order_product_board_model->has_brothers($this->_V))) {
            $this->_OrderProduct = $Query[array_rand($Query, ONE)];
            return true;
        }
        return false;
    }
}
