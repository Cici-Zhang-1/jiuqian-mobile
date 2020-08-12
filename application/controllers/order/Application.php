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
        'application_id' => ZERO,
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
        if (!empty($this->_Search['application_id']) || !empty($this->_Search['order_id'])) {
            $this->_Search['status'] = array(
                PASS,
                PASSED,
                UNPASS
            );
        } else {
            if (is_string($this->_Search['status'])) {
                $this->_Search['status'] = explode(',', $this->_Search['status']);
            }
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
            if(!!($Order = $this->order_model->are_applicable($Post['v'], array(O_WAIT_SURE), EASY_PRODUCE))) {
                $EasyProduceAutoPass = $this->_get_configs('easy_produce_auto_pass');
                $MaxEasyProduce = $this->_get_configs('max_easy_produce');
                $MaxEasyProduce = -1 * intval($MaxEasyProduce);
                $Post['v'] = array();
                $Data = array();
                $Ding = array();
                $Dealer = array();
                foreach ($Order as $Key => $Value) {
                    if ($this->_is_only_server($Value['v'])) {
                        $Data = array();
                        $this->Code = EXIT_ERROR;
                        $this->Message = $Value['num'] . '只包含服务类产品，必须全款到账!';
                        break;
                    }
                    $NeedPay = floor($Value['sum'] * $Value['down_payment']);
                    if (!isset($Dealer[$Value['dealer_id']])) {
                        $Dealer[$Value['dealer_id']] = $Value['dealer_balance'];
                    }
                    $Dealer[$Value['dealer_id']] = bcsub($Dealer[$Value['dealer_id']], $NeedPay, 2);
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
                            'remark' => $Post['a_remark'] . ', 金额:￥' . $NeedPay
                        );
                        if ($EasyProduceAutoPass || $MaxEasyProduce <= $Dealer[$Value['dealer_id']]) { // 当设置为自动通过或者客户余额大于最大宽松金额时，可以自动通过宽松生产
                            $Tmp['status'] = PASSED;
                            $Tmp['replyer'] = $this->session->userdata('uid');
                            $Tmp['reply_datetime'] = date('Y-m-d H:i:s');
                        } else {
                            $Tmp['replyer'] = ZERO;
                            $Tmp['reply_datetime'] = null;
                            array_push($Ding, $Value['num']);
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
                            $W->store_message('订单申请宽松生产');
                            $dd = $this->_send_dd_msg($Ding, '宽松生产');
                            log_message('debug', 'dingding send' . $dd);
                        }
                    }
                } elseif ($this->Code == EXIT_SUCCESS) {
                    $this->Message = '新建成功, 刷新后生效!';
                }
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    private function _is_only_server ($OrderId) {
        $this->load->model('order/order_product_model');
        if (!!($OrderProduct = $this->order_product_model->select_by_order_id($OrderId))) {
            $OnlyServer = true;
            foreach ($OrderProduct as $Key => $Value) {
                if ($Value['code'] != SERVER_NUM) {
                    $OnlyServer = false;
                }
            }
            return $OnlyServer;
        }
        return false;
    }
    /**
     * 宽松发货
     */
    public function easy_delivery () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $this->load->model('order/order_model');
            if(!!($Order = $this->order_model->are_applicable($Post['v'], array(O_WAIT_DELIVERY), EASY_DELIVERY))) {
                $EasyDeliveryAutoPass = $this->_get_configs('easy_delivery_auto_pass');
                $MaxEasyDelivery = $this->_get_configs('max_easy_delivery');
                $MaxEasyDelivery = -1 * intval($MaxEasyDelivery);
                $Post['v'] = array();
                $Data = array();
                $Ding = array();
                $Dealer = array();
                foreach ($Order as $Key => $Value) {
                    $NeedPay = floor($Value['sum'] - $Value['payed']);
                    if (!isset($Dealer[$Value['dealer_id']])) {
                        $Dealer[$Value['dealer_id']] = $Value['dealer_balance'];
                    }
                    $Dealer[$Value['dealer_id']] = bcsub($Dealer[$Value['dealer_id']], $NeedPay, 2);
                    if (ZERO <= $Dealer[$Value['dealer_id']]) { // 低于客户余额的不需要申请宽松发货，但是从余额中扣除需要支付的金额
                        continue;
                    } else {
                        array_push($Post['v'], $Value['v']);
                        $Tmp = array(
                            'type' => 'payterms',
                            'source_id' => $Value['v'],
                            'source' => $Value['payterms'],
                            'des' => EASY_DELIVERY,
                            'status' => PASS,
                            'remark' => $Post['a_remark'] . ', 金额:￥' . $NeedPay
                        );
                        if ($EasyDeliveryAutoPass || $MaxEasyDelivery <= $Dealer[$Value['dealer_id']]) { // 当设置为自动通过或者客户余额大于最大宽松金额时，可以自动通过宽松生产
                            $Tmp['status'] = PASSED;
                            $Tmp['replyer'] = $this->session->userdata('uid');
                            $Tmp['reply_datetime'] = date('Y-m-d H:i:s');
                        } else {
                            $Tmp['replyer'] = ZERO;
                            $Tmp['reply_datetime'] = null;
                            array_push($Ding, $Value['num']);
                        }
                        array_push($Data, $Tmp);
                    }
                }
                if (!empty($Data)) {
                    $this->order_model->trans_start();
                    $this->order_model->update(array('payterms' => EASY_DELIVERY), $Post['v']);
                    $this->application_model->insert_batch($Data);
                    $this->order_model->trans_complete();
                    if ($this->order_model->trans_status() == false) {
                        $this->Message = '新建时提交数据发生错误!';
                        $this->Code = EXIT_ERROR;
                    } else {
                        $this->load->library('workflow/workflow');
                        $W = $this->workflow->initialize('order');
                        if ($W->initialize($Post['v'])) {
                            $W->store_message('订单申请宽松发货');
                            $dd = $this->_send_dd_msg($Ding, '宽松发货');
                            log_message('debug', 'dingding send' . $dd);
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

    private function _send_dd_msg ($Ding, $Type) {
        if (!!($User = $this->_finance())) {
            require_once APPPATH . 'third_party/eapp/send_application.php';
            return send(array("msgtype" => 'link', 'link' => array(
                "messageUrl" => "eapp://pages/application/application",
                "picUrl" => "@lALOACZwe2Rk",
                "title" => $this->session->userdata('truename') . $Type,
                "text" => date('Y-m-d H:i:s') . implode(',', $Ding)
            )), $User);
        } else {
            return '没有财务账户可以发送';
        }
    }

    /**
     * 获取财务人员信息
     * @return array|bool
     */
    private function _finance() {
        $this->load->model('permission/usergroup_model');
        $this->load->model('manage/user_model');
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('财务管理员'))) {
            $this->get_page_search();
            $this->_Search['usergroup_v'] = $UsergroupV;
            $Data = array();
            if(!($Data = $this->user_model->select($this->_Search))){
                log_message('debug', 'dddddddd' . '没有user');
                return false;
            } else {
                $User = array();
                foreach ($Data['content'] as $Key => $Value) {
                    if (!empty($Value['user_id'])) {
                        array_push($User, $Value['user_id']);
                    }
                }
                if (!empty($User)) {
                    return $User;
                }
                log_message('debug', 'dddddddd' . '没有user_id');
                return false;
            }
        } else {
            log_message('debug', 'dddddddd' . '没有usergroup');
            return false;
        }
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
     * 通过申请
     */
    public function passed () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            $Post = array(
                'status' => PASSED,
                'replyer' => $this->session->userdata('uid'),
                'reply_datetime' => date('Y-m-d H:i:s')
            );
            if(!!($this->application_model->update($Post, $Where))){
                $this->Message = '申请通过成功, 刷新后生效!';
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
