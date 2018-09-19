<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order stock outted Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Order_stock_outted extends MY_Controller {
    private $_Order = array();
    private $_OrderProduct = array();
    private $_PackDetail = array();
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Order_stock_outted __construct Start!');
        $this->load->model('warehouse/order_stock_outted_model');
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
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->order_stock_outted_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
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
            if(!!($NewId = $this->order_stock_outted_model->insert($Post))) {
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
            if(!!($this->order_stock_outted_model->update($Post, $Where))){
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
        if ($this->_do_form_validation()) {
            $V = $this->input->post('v', true);
            $this->load->model('warehouse/order_stock_outted_model');
            if (!!($Query = $this->order_stock_outted_model->is_re_deliverable($V))) {
                $PackDetail = json_decode($Query['pack_detail'], true);
                $OrderProduct = array();
                foreach ($PackDetail as $Ikey => $Ivalue) {
                    if (!isset($OrderProduct[$Ivalue['v']])) {
                        $this->_OrderProduct[$Ivalue['v']] = 0;
                    }
                    $this->_OrderProduct[$Ivalue['v']] += $Ivalue['pack'];
                    $this->_PackDetail[$Ivalue['v']] = $Ivalue;
                }
                $this->order_stock_outted_model->trans_begin();
                $this->_re_delivery_order_product($Query['order_id']);
                $this->_re_delivery_order();
                if (empty($this->_PackDetail)) {
                    $Set = array(
                        'status' => ZERO
                    );
                } else {
                    $Set = array(
                        'pack' => array_sum($this->_OrderProduct),
                        'pack_detail' => json_encode($this->_PackDetail)
                    );
                }
                $this->load->model('warehouse/stock_outted_model');
                if (!($this->stock_outted_model->update($Set, $Query['stock_outted_id']))) {
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'重新发货失败';
                    $this->Code = EXIT_ERROR;
                } else {
                    $this->order_stock_outted_model->delete($Query['v']);
                    $this->_workflow(array_keys($this->_Order));
                }
                if ($this->order_stock_outted_model->trans_status() === FALSE) {
                    $this->order_stock_outted_model->trans_rollback();
                    $this->Message = '在重新返回发货时出错';
                    $this->Code = EXIT_ERROR;
                } else {
                    if ($this->Code == EXIT_SUCCESS) {
                        $this->order_stock_outted_model->trans_commit();
                        $this->Message = '重新发货成功!';
                    } else {
                        $this->order_stock_outted_model->trans_rollback();
                    }
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '这个订单不能重新发货不存在!';
            }
        }
        $this->_ajax_return();
    }
    /**
     * 重新发货订单
     * @param $Order
     * @return bool
     */
    private function _re_delivery_order () {
        $this->load->model('order/order_model');
        if (!!($Query = $this->order_model->select_delivered(array_keys($this->_Order)))) {
            foreach ($Query as $Key => $Value) {
                $Value['delivered'] = $Value['delivered'] - $this->_Order[$Value['v']];
                $Query[$Key] = $Value;
            }
            $this->order_stock_outted_model->trans_rollback();
            if (!($this->order_model->update_batch($Query))) {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单重新发货失败!';
                return false;
            }
        }
        return true;
    }

    /**
     * 重新发货订单产品
     * @param $OrderId
     * @return bool
     */
    private function _re_delivery_order_product ($OrderId) {
        $this->load->model('order/order_product_model');
        if (!!($Query = $this->order_product_model->select_delivered_by_v(array_keys($this->_OrderProduct), $OrderId))) {
            foreach ($Query as $Key => $Value) {
                $Value['delivered'] = $Value['delivered'] - $this->_OrderProduct[$Value['v']];
                if (!isset($this->_Order[$Value['order_id']])) {
                    $this->_Order[$Value['order_id']] = 0;
                }
                $this->_Order[$Value['order_id']] += $this->_OrderProduct[$Value['v']];
                unset($this->_PackDetail[$Value['v']], $this->_OrderProduct[$Value['v']]);
                unset($Value['order_id']);
                $Query[$Key] = $Value;
            }
            if (!($this->order_product_model->update_batch($Query))) {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单产品重新发货失败!';
                return false;
            }
        }
        return true;
    }

    /**
     * 工作流
     * @param $V
     * @return bool
     */
    private function _workflow ($V) {
        $this->load->library('workflow/workflow');
        $W = $this->workflow->initialize('order');
        $W->initialize($V);
        if (!$W->re_delivery()) {
            $this->Code = EXIT_ERROR;
            $this->Message = $W->get_failue();
            return false;
        }
        return true;
    }
}
