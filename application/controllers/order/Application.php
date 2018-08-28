<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Application extends MY_Controller {
    private $__Search = array(
        'order_id' => ZERO,
        'status' => PASS
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Application __construct Start!');
        $this->load->model('order/application_model');
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
        if (empty($this->_Search['order_id'])) {
            $this->_Search['order_id'] = $this->input->get('v', true);
            $this->_Search['order_id'] = intval($this->_Search['order_id']);
        }
        if (is_string($this->_Search['status'])) {
            $this->_Search['status'] = explode(',', $this->_Search['status']);
        }
        $Data = array();
        if(!($Data = $this->application_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        if (!empty($this->_Search['order_id'])) {
            $Data['query']['order_id'] = $this->_Search['order_id'];
        }
        $this->_ajax_return($Data);
    }

    /**
     *
     * @return void
     */
    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if(!!($NewId = $this->application_model->insert($Post))) {
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    /**
     * 宽松生产
     */
    public function easy_produce () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $this->load->model('order/order_model');
            if(!!($Order = $this->order_model->are_applicable($Post['v'], array(O_WAIT_SURE)))) {
                $EasyProduceAutoPass = $this->_get_configs('easy_produce_auto_pass');
                $MaxEasyProduce = $this->_get_configs('max_easy_produce');
                $MaxEasyProduce = -1 * intval($MaxEasyProduce);
                $Post['v'] = array();
                $Data = array();
                $Dealer = array();
                foreach ($Order as $Key => $Value) {
                    $NeedPay = floor($Value['sum'] * $Value['down_payment']);
                    if (!isset($Dealer[$Value['dealer_id']])) {
                        $Dealer[$Value['dealer_id']] = $Value['dealer_balance'];
                    }
                    $Dealer[$Value['dealer_id']] = $Dealer[$Value['dealer_id']] - $NeedPay;
                    if (ZERO <= $Dealer[$Value['dealer_id']]) { // 低于客户余额的不需要申请宽松生产，但是从余额中扣除需要支付的金额
                        continue;
                    } else {
                        array_push($Post['v'], $Value['v']);
                        $Tmp = array(
                            'type' => 'payterms',
                            'source_id' => $Value['v'],
                            'source' => $Value['payterms'],
                            'des' => EASY_PRODUCE,
                            'status' => PASS,
                            'remark' => $Post['remark']
                        );
                        if ($EasyProduceAutoPass || $MaxEasyProduce <= $Dealer[$Value['dealer_id']]) { // 当设置为自动通过或者客户余额大于最大宽松金额时，可以自动通过宽松生产
                            $Tmp['status'] = PASSED;
                            $Tmp['replyer'] = $this->session->userdata('uid');
                            $Tmp['reply_datetime'] = date('Y-m-d H:i:s');
                        } else {
                            $Tmp['replyer'] = ZERO;
                            $Tmp['reply_datetime'] = null;
                        }
                        array_push($Data, $Tmp);
                    }
                }
                if (!empty($Data)) {
                    $this->order_model->trans_start();
                    $this->order_model->update(array('payterms' => EASY_PRODUCE), $Post['v']);
                    $this->application_model->insert_batch($Data);
                    $this->order_model->trans_complete();
                    if ($this->order_model->trans_status() == false) {
                        $this->Message = '新建时提交数据发生错误!';
                        $this->Code = EXIT_ERROR;
                    } else {
                        $this->load->library('workflow/workflow');
                        $W = $this->workflow->initialize('order');
                        if ($W->initialize($Post['v'])) {
                            $W->store_message('订单申请宽松支付');
                        }
                    }
                }
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    /**
    *
    * @return void
    */
    public function edit() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            $Post['replyer'] = $this->session->userdata('uid');
            $Post['reply_datetime'] = date('Y-m-d H:i:s');
            if(!!($this->application_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
    }

    /**
     *
     * @param  int $id
     * @return void
     */
    public function remove() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->application_model->update(array('status' => PASSED),$Where)) {
                $this->Message = '申请通过成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'申请通过失败!';
            }
        }
        $this->_ajax_return();
    }
}
