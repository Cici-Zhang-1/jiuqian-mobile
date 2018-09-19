<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pick sheet Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Pick_sheet extends MY_Controller {
    private $_Order = array();
    private $_OrderProduct = array();
    private $__Search = array(
        'status' => 1
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Pick_sheet __construct Start!');
        $this->load->model('warehouse/stock_outted_model');
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
            if (empty($this->_Search['keyword'])) {
                $this->_Search['start_date'] = date('Y-m-d');
            } else {
                $this->_Search['start_date'] = date('Y-m-d', gh_to_sec($this->_Search['keyword']));
            }
        } else {
            $this->_Search['start_date'] = date('Y-m-d', gh_to_sec($this->_Search['start_date']));
        }
        $Data = array();
        if(!($Data = $this->stock_outted_model->select($this->_Search))){
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
            if(!!($NewId = $this->stock_outted_model->insert($Post))) {
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
            if(!!($this->stock_outted_model->update($Post, $Where))){
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
            if ($this->stock_outted_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }

    public function re_delivery(){
        $V = $this->input->post('v', true);
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $V = $this->input->post('v', true);
            $this->load->model('warehouse/order_stock_outted_model');
            if (!!($Query = $this->order_stock_outted_model->are_re_deliverable_by_stock_outted_id($V))) {
                $Order = array();
                $OrderProduct = array();
                $ReDeliverable = array();
                foreach ($Query as $Key => $Value) {
                    $PackDetail = json_decode($Value['pack_detail'], true);
                    foreach ($PackDetail as $Ikey => $Ivalue) {
                        if (!isset($OrderProduct[$Ivalue['v']])) {
                            $OrderProduct[$Ivalue['v']] = 0;
                        }
                        $OrderProduct[$Ivalue['v']] += $Ivalue['pack'];
                    }
                    if (!in_array($Value['stock_outted_id'], $ReDeliverable)) {
                        array_push($ReDeliverable, $Value['stock_outted_id']);
                    }
                }
                $this->order_stock_outted_model->trans_begin();
                $this->_re_delivery_order_product($OrderProduct);
                $this->_re_delivery_order($Order);
                if (!($this->stock_outted_model->update(array('status' => 0), $ReDeliverable))) {
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'重新发货失败';
                    $this->Code = EXIT_ERROR;
                } else {
                    $this->_workflow(array_keys($Order));
                }
                if ($this->order_stock_outted_model->trans_status() === FALSE) {
                    $this->order_stock_outted_model->trans_rollback();
                    $this->Message = '在重新返回发货时出错';
                    $this->Code = EXIT_ERROR;
                } else {
                    if ($this->Code == EXIT_SUCCESS) {
                        $this->order_stock_outted_model->trans_commit();
                    } else {
                        $this->order_stock_outted_model->trans_rollback();
                    }
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '要重新发货的发货单不存在!';
            }
        }
        $this->_ajax_return();
    }

    private function _re_delivery_order () {
        $this->load->model('order/order_model');
        if (!!($Query = $this->order_model->select_delivered(array_keys($this->_Order)))) {
            foreach ($Query as $Key => $Value) {
                $Value['delivered'] = $Value['delivered'] - $this->_Order[$Value['v']];
                $Query[$Key] = $Value;
            }
            if (!($this->order_model->update_batch($Query))) {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单重新发货失败!';
                return false;
            }
        }
        return true;
    }
    private function _re_delivery_order_product ($OrderProduct) {
        $this->load->model('order/order_product_model');
        if (!!($Query = $this->order_product_model->select_delivered_by_v(array_keys($OrderProduct)))) {
            foreach ($Query as $Key => $Value) {
                $Value['delivered'] = $Value['delivered'] - $OrderProduct[$Value['v']];
                $Query[$Key] = $Value;
                if (!isset($this->_Order[$Value['order_id']])) {
                    $this->_Order[$Value['order_id']] = 0;
                }
                $this->_Order[$Value['order_id']] += $OrderProduct[$Value['v']];
            }
            if (!($this->order_product_model->update_batch($Query))) {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单产品重新发货失败!';
                return false;
            }
        }
        return true;
    }

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
