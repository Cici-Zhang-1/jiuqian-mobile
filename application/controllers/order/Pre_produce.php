<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pre produce Controller
 *
 * @package  CodeIgniter
 * @category Controller
 * @des 生产预处理
 */
class Pre_produce extends MY_Controller {
    private $__Search = array(
        'status' => array(OP_PRE_PRODUCING),
        'all' => YES
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Pre_produce __construct Start!');
        $this->load->model('order/order_product_model');
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->order_product_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    public function to_board () {
        if (!!($Query = $this->_pre_handle())) {
            $this->load->library('workflow/workflow');
            $W = $this->workflow->initialize('order_product');
            if ($W->initialize($Query)) {
                $W->to_board();
            } else {
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']: $W->get_failue();
                $this->Code = EXIT_ERROR;
            }
        } else {
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'当前状态不可以转换';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return();
    }

    public function to_classify () {
        if (!!($Query = $this->_pre_handle())) {
            $this->load->library('workflow/workflow');
            $W = $this->workflow->initialize('order_product');
            if ($W->initialize($Query)) {
                $W->to_classify();
            } else {
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']: $W->get_failue();
                $this->Code = EXIT_ERROR;
            }
        } else {
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'当前状态不可以转换';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return();
    }

    private function _pre_handle () {
        $V = $this->input->post('v', true);
        if (!is_array($V)) {
            $V = explode(',', $V);
        }
        foreach ($V as $Key => $Value) {
            $V[$Key] = intval(trim($Value));
        }
        $this->load->model('order/order_product_model');
        if (!!($Query = $this->order_product_model->are_status($V, array(OP_DISMANTLED, OP_PRE_PRODUCING)))) {
            foreach ($Query as $Key => $Value) {
                $Query[$Key] = $Value['order_product_id'];
            }
            return $Query;
        }
        return false;
    }
}
