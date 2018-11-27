<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 2017/10/17
 * Time: 11:06
 *
 * Desc:
 */
class Order_product extends MY_Controller {
    private $__Search = array(
        'status' => '',
        'product_id' => '',
        'all' => NO,
        'keyword' => '',
        'order_id' => ZERO
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Order_product __construct Start!');
        $this->load->model('order/order_product_model');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read() {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['order_id'])) {
            $OrderId = $this->input->get('v', true);
            $OrderId = intval($OrderId);
            if (!empty($OrderId)) {
                $this->_Search['order_id'] = $OrderId;
            }
        }
        if ($this->_Search['product_id'] !== '') {
            $this->_Search['product_id'] = explode(',', $this->_Search['product_id']);
        } else {
            $this->_Search['product_id'] = array();
        }
        if (!is_array($this->_Search['status'])) {
            if ($this->_Search['status'] != '') {
                $this->_Search['status'] = explode(',', $this->_Search['status']);
            } else {
                $this->_Search['status'] = array(
                    OP_CREATE,
                    OP_DISMANTLING
                );
            }
        }

        $Data = array();
        if(!($Data = $this->order_product_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        if (!empty($this->_Search['order_id'])) {
            $Data['query']['order_id'] = $this->_Search['order_id'];
        }
        $this->_ajax_return($Data);
    }

    public function detail () {
        $this->_Search['order_product_id'] = ZERO;
        $this->get_page_search();
        if (empty($this->_Search['order_product_id'])) {
            $this->_Search['order_product_id'] = $this->input->get('v', true);
            $this->_Search['order_product_id'] = intval($this->_Search['order_product_id']);
        }

        $Data = array();
        if (!empty($this->_Search['order_product_id'])) {
            if(!($Data = $this->order_product_model->select_detail($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单详情不存在';
                $this->Code = EXIT_ERROR;
            } else {
                $Data['content']['order_product'] = $this->_get_order_product_num($Data['content']['order_id']);
            }
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        } else {
            $this->Message = '请选择订单获取订单详情';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    private function _get_order_product_num ($OrderId) {
        $Data = array();
        if ($Query = $this->order_product_model->select_by_order_id($OrderId)) {
            foreach ($Query as $Key => $Value) {
                $Tmp = explode('-', $Value['num']);
                $Data[] = array_pop($Tmp);
            }
        }
        return implode(',', $Data);
    }

    public function add () {
        $Product = $this->input->post('product', true);
        $_POST['product'] = explode(',', $Product);
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $ProductId = $Post['product_id'];
            $OrderId = $Post['order_id'];
            $Set = $Post['set'];
            unset($Post['product_id'], $Post['order_product_id'], $Post['set']);
            $this->load->model('product/product_model');
            $this->load->model('order/order_model');
            if (!($Product = $this->product_model->is_exist($ProductId))) {
                $this->Code = EXIT_ERROR;
                $this->Message .= '产品不存在!';
            } elseif (!($this->order_model->is_dismantlable($OrderId))) {
                $this->Code = EXIT_ERROR;
                $this->Message .= '订单不存在或者当前状态不能新建订单产品!';
            } elseif(!!($Query = $this->order_product_model->insert(array($Product), $OrderId, $Set, $Post))){
                $this->load->library('workflow/workflow');
                $W = $this->workflow->initialize('order_product');
                foreach ($Query as $key => $value){
                    $Query[$key] = $value['v'];
                }
                if(!!($W->initialize($Query))){
                    $W->create();
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品新增失败!';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 订单产品作废
     */
    public function remove(){
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            $this->load->library('workflow/workflow');
            $W = $this->workflow->initialize('order_product');
            if ($W->initialize($Where) && $W->remove()) {
                $this->Message = '订单产品作废成功!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = $W->get_failue();
            }
        }
        $this->_ajax_return();
    }

    /**
     * 复制订单
     */
    public function repeat () {
        if ($this->_do_form_validation()) {
            $V = $this->input->post('v', true);
            $Set = $this->input->post('set', true);
            if (!!($OrderProduct = $this->order_product_model->is_order_dismantlable($V))) {
                $OrderId = $OrderProduct['order_id'];
                $Product = array(
                    'v' => $OrderProduct['product_id'],
                    'name' => $OrderProduct['product'],
                    'code' => $OrderProduct['code']
                );
                $Data = array(
                    'remark' => $OrderProduct['remark'],
                    'product' => $OrderProduct['product']
                );
                if(!!($Query = $this->order_product_model->insert(array($Product), $OrderId, $Set, $Data))){
                    $this->load->library('workflow/workflow');
                    $W = $this->workflow->initialize('order_product');
                    $this->load->library('d/d');
                    $D = $this->d->initialize($OrderProduct['code']);
                    foreach ($Query as $key => $value){
                        if(!!($W->initialize($value['v']))){
                            $W->create();
                            if (!($D->repeat($value, $OrderProduct))) {
                                $this->Code = EXIT_ERROR;
                                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单板块复制失败!';
                                break;
                            }
                        }else{
                            $this->Code = EXIT_ERROR;
                            $this->Message = $W->get_failue();
                            break;
                        }
                    }
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品新增失败!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message .= '订单产品状态不可复制!';
            }
        }
        $this->_return();
    }

    /**
     * 订单复制到
     */
    public function repeat_to () {
        if ($this->_do_form_validation()) {
            $V = $this->input->post('v', true);
            $To = $this->input->post('to', true);
            if (!!($From = $this->order_product_model->is_exist('', $V))) {
                $To = gh_escape($To);
                if (!!($To = $this->order_product_model->is_order_dismantlable($To))) {
                    if ($To['code'] !== $From['code']) {
                        $this->Code = EXIT_ERROR;
                        $this->Message .= '请选择相同类型的订单复制!';
                    } else {
                        $this->load->library('d/d');
                        $D = $this->d->initialize($From['code']);
                        if (!($D->repeat($To, $From))) {
                            $this->Code = EXIT_ERROR;
                            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单板块复制失败!';
                        } else {
                            $this->load->library('workflow/workflow');
                            $W = $this->workflow->initialize('order_product');
                            $W->initialize($To['v']);
                            $W->store_message('从' . $From['num'] . '复制订单数据');
                            $this->Message .= '复制订单成功!';
                        }
                    }
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message .= '您要复制到的订单不存在!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message .= '您要复制的订单不存在!';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 设计图集
     */
    public function design_atlas () {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $V = $Post['v'];
            unset($Post['v']);
            if(!!($this->order_product_model->update($Post, $V))){
                $this->Message = '设计图集添加成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'设计图集添加失败';
            }
        }
        $this->_ajax_return();
    }
}
