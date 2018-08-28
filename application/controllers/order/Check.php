<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月23日
 * @author Zhangcc
 * @version
 * @des
 * 等待核价
 */
 
class Check extends MY_Controller{
    private $Search = array(
        'status' => O_CHECK,
        'keyword' => '',
        'all' => YES,
        'order_id' => ZERO
    );

    private $_OrderId;
    
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Check Start!');
        $this->load->model('order/order_model');
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->Search);
        $this->get_page_search();
        if (empty($this->_Search['order_id'])) {
            $OrderId = $this->input->get('v', true);
            $this->_Search['order_id'] = intval($OrderId);
        }
        $Data = array();
        if(!($Data = $this->order_model->select($this->_Search, '_check'))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        if (!empty($this->_Search['order_id'])) {
            $Data['query']['order_id'] = $this->_Search['order_id'];
        }

        $this->_ajax_return($Data);
    }

    /**
     * 添加批注
     */
    public function add () {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            $this->load->model('order/order_datetime_model');
            if(!!($NewId = $this->order_datetime_model->update($Post, $Where))) {
                $this->Message = '批注成功, 刷新后生效!';
            } else {
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'批注失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    /**
     * 审核通过
     */
    public function checked () {
        $V = $this->input->post('v', true);
        $_POST['v'] = is_array($V) ? $V : explode(',', $V);
        unset($V);
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if (!!($Order = $this->order_model->are_status($Where, array(O_CHECK)))) {
                foreach ($Order as $Key => $Value) {
                    $this->_OrderId = $Value['v'];
                    if (!($this->_workflow('checked'))) {
                        break;
                    }
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '没有可以确认核价的订单!';
            }
        }
        $this->_ajax_return();
    }

    private function _workflow ($Save) {
        $this->load->library('workflow/workflow');
        $W = $this->workflow->initialize('order');

        if(!!($W->initialize($this->_OrderId))){
            $W->{$Save}();
            $this->Message = '财务审核通过!';
            return true;
        }else{
            $this->Code = EXIT_ERROR;
            $this->Message = $W->get_failue();
            return false;
        }
    }
}
