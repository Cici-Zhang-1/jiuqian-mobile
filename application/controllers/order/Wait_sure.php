<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 */
class Wait_sure extends MY_Controller{
    private $Search = array(
        'keyword' => '',
        'all' => NO,
    );
    private $_OrderId;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Wait_sure Start!');
        $this->load->model('order/order_model');
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->order_model->select_wait_sure($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            foreach ($Data['content'] as $Key => $Value) {
                $Data['content'][$Key]['need_pay'] = floor($Value['sum'] * $Value['down_payment']);
            }
        }
        if (!empty($this->_Search['order_id'])) {
            $Data['query']['order_id'] = $this->_Search['order_id'];
        }

        $this->_ajax_return($Data);
    }

    public function add () {
        $V = $this->input->post('v', TRUE);
        $_POST['v'] = is_array($V) ? $V : explode(',', $V);
        if ($this->_do_form_validation()) {
            $V = $this->input->post('v', true);
            $Post = array(
                'request_outdate' => $this->input->post('request_outdate', true)
            );
            if (!!($Order = $this->order_model->are_status($V, O_WAIT_SURE))) {
                $this->load->library('workflow/workflow');
                $W = $this->workflow->initialize('order');
                $this->Message = '订单确认生产成功!';
                foreach ($Order as $Key => $Value) {
                    if ($W->initialize($Value['v'], $Post)) {
                        if ($W->produce() === false) {
                            $this->Code = EXIT_ERROR;
                            $this->Message = $W->get_failue();
                            break;
                        }
                    } else {
                        $this->Code = EXIT_ERROR;
                        $this->Message = $W->get_failue();
                        break;
                    }
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '当前订单状态不可以确认生产';
            }
        }
        $this->_ajax_return();
    }

    public function edit () {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            if (!empty($Post['down_payment_sum']) && !empty($Post['sum'])) {
                $Post['down_payment'] = floor(($Post['down_payment_sum'] / $Post['sum']) * M_TWO) / M_TWO;
            }
            if ($Post['down_payment'] < MIN_DOWN_PAYMENT) {
                $Post['down_payment'] = MIN_DOWN_PAYMENT;
            }
            if(!!($this->order_model->update($Post, $Where))){
                $this->Message = '订单修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单修改失败';
            }
        }
        $this->_ajax_return();
    }
    /**
     * 重新确认
     */
    public function re_sure() {
        if ($this->_do_form_validation()) {
            $V = $this->input->post('v', true);
            if (!!($this->order_model->is_status($V, O_PRODUCE))) {
                $this->load->library('workflow/workflow');
                $W = $this->workflow->initialize('order');
                if ($W->initialize($V)) {
                    $W->re_sure();
                    $this->Message = '订单产品重新安排确认成功!';
                    $this->Location = '/order/wait_sure/index/read';
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '这个订单不能已经不能重新安排确认!';
            }
        }
        $this->_ajax_return();
    }
}
