<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Zhangcc
 * @version
 * @des
 * 等待发货
 */
class Wait_delivery extends MY_Controller{
    private $_Delivered = array();
    private $_Delivering = array();
    private $_StockOutedId;
    private $_OrderProduct = array();
    private $_W;
    private $__Search = array(
        'paging' => NO,
        'all' => YES,
        'out_method' => ''
    );
    
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Wait_delivery Start!');
        $this->load->model('order/order_model');
        $this->config->load('defaults/out_method');
    }

    private function _read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();

        if(!($Data = $this->order_model->select_wait_delivery($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取等待发货订单失败';
            $this->Code = EXIT_ERROR;
        } else {
            foreach ($Data['content'] as $Key => $Value) {
                $PackDetail = json_decode($Value['pack_detail'], true);
                if (is_array($PackDetail)) {
                    $Value = array_merge($Value, $PackDetail);
                    $Data['content'][$Key] = $Value;
                }
            }
        }
        $this->_ajax_return($Data);
    }

    public function read () {
        $this->_read();
    }
    /**
     * 物流到厂
     */
    public function to_factory(){
        $this->__Search['out_method'] = $this->config->item('to_factory');
        $this->_read();
    }

    public function to_logistics(){
        $this->__Search['out_method'] = $this->config->item('to_logistics');
        $this->_read();
    }

    public function to_dadao(){
        $this->__Search['out_method'] = $this->config->item('to_dadao');
        $this->_read();
    }

    public function work_out() {
        $V = $this->input->get('v', true);
        if (!is_array($V)) {
            $V = explode(',', $V);
        }
        foreach ($V as $Key => $Value) {
            $Value = intval(trim($Value));
            if (!empty($Value)) {
                $V[$Key] = $Value;
            } else {
                unset($V[$Key]);
            }
        }
        $Data = array();
        if (!empty($V)) {
            $this->load->model('order/order_product_model');
            if (!($Data = $this->order_product_model->select_work_out($V))) {
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取等待发货订单失败';
                $this->Code = EXIT_ERROR;
            } else {
                $this->load->helper('json_helper');
                foreach ($Data['content'] as $Key => $Value) {
                    $Value['pack_detail'] = discode_pack($Value['pack_detail']);
                    $Data['content'][$Key] = $Value;
                }
            }
        } else {
            $this->Message = '请选择需要发货的订单';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    /**
     * 发货单打印，则确认为已发货
     */
    public function edit(){
        if ($this->_do_form_validation()) {
            $OrderProduct = $this->input->post('order_product', true);
            $Pack = 0;
            $PackDetail = array();
            $Collection = array();
            foreach ($OrderProduct as $Key => $Value) {
                if (($Value['wait_delivery'] + $Value['delivered']) > $Value['pack']) {
                    $Value['wait_delivery'] = $Value['pack'] - $Value['delivered'];
                }
                if ($Value['wait_delivery'] > 0) {
                    if (($Value['wait_delivery'] + $Value['delivered']) < $Value['pack']) {
                        if (!in_array($Value['order_id'], $this->_Delivering)) {
                            array_push($this->_Delivering, $Value['order_id']);
                        }
                        $In = array_search($Value['order_id'], $this->_Delivered);
                        if ($In !== false) {
                            array_splice($this->_Delivered, $In, 1);
                        }
                    } else {
                        if (!in_array($Value['order_id'], $this->_Delivering)) {
                            array_push($this->_Delivered, $Value['order_id']);
                        }
                    }
                    if (!isset($Collection[$Value['order_id']])) {
                        $Collection[$Value['order_id']] = $Value['collection'];
                    }
                    $Pack += $Value['wait_delivery']; // 统计实际发货件数
                    $Value['delivered'] += $Value['wait_delivery'];  // 暂存已发货件数
                    array_push($PackDetail, array(
                        'v' => $Value['v'],
                        'pack' => $Value['wait_delivery']
                    ));
                    array_push($this->_OrderProduct, array(
                        'v' => $Value['v'],
                        'delivered' => $Value['delivered']
                    ));
                }
            }
            unset($OrderProduct);
            if ($Pack > 0) {
                $Set = array(
                    'num' => 'F' . date('YmdHis') . mt_rand(),
                    'truck' => $this->input->post('truck', true),
                    'train' => $this->input->post('train', true),
                    'end_datetime' => $this->input->post('end_datetime', true),
                    'collection' => array_sum($Collection), // 代收金额
                    'pack' => $Pack, // 发货件数
                    'pack_detail' => '' // 明细
                );
                $this->load->model('warehouse/stock_outted_model');
                $Set = gh_escape($Set);
                $Set['pack_detail'] = json_encode($PackDetail);
                $this->stock_outted_model->trans_begin();
                if (!!($this->_StockOutedId = $this->stock_outted_model->insert($Set))) {
                    $this->_workflow();
                    $this->_edit_order_product();
                    $this->_add_order_stock_outted();
                } else {
                    $this->Message = '新建发货单时出错';
                    $this->Code = EXIT_ERROR;
                }
                if ($this->stock_outted_model->trans_status() === FALSE){
                    $this->stock_outted_model->trans_rollback();
                    $this->Message = '在确定拟定的发货单时出错';
                    $this->Code = EXIT_ERROR;
                } else {
                    if ($this->Code == EXIT_SUCCESS) {
                        $this->stock_outted_model->trans_commit();
                        $this->_send_dd_msg($Set['num']);
                        $this->Location = '/warehouse/pick_sheet_print?v=' . $this->_StockOutedId;
                    } else {
                        $this->stock_outted_model->trans_rollback();
                    }
                }
            } else {
                $this->Message = '请选择要发货的订单';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    private function _add_order_stock_outted () {
        $Set = array();
        if (!empty($this->_Delivered)) {
            if (!!($Query = $this->order_product_model->select_delivered($this->_Delivered))) {
                foreach ($Query as $Key => $Value) {
                    array_push($Set, array(
                        'order_id' => $Value['order_id'],
                        'stock_outted_id' => $this->_StockOutedId
                    ));
                    if (!$this->_edit_delivered($Value['order_id'], $Value['delivered'])) {
                        return false;
                    }
                }
            } else {
                $this->Message = '在获取已经发货件数时出错';
                $this->Code = EXIT_ERROR;
                return false;
            }
        }
        if (!empty($this->_Delivering)) {
            if (!!($Query = $this->order_product_model->select_delivered($this->_Delivering))) {
                foreach ($Query as $Key => $Value) {
                    array_push($Set, array(
                        'order_id' => $Value['order_id'],
                        'stock_outted_id' => $this->_StockOutedId
                    ));
                    if (!$this->_edit_delivering($Value['order_id'], $Value['delivered'])) {
                        return false;
                    }
                }
            } else {
                $this->Message = '在获取已经发货件数时出错';
                $this->Code = EXIT_ERROR;
                return false;
            }
        }
        if (!empty($Set)) {
            $this->load->model('warehouse/order_stock_outted_model');
            return $this->order_stock_outted_model->insert_batch($Set);
        } else {
            $this->Message = '发生错误';
            $this->Code = EXIT_ERROR;
            return false;
        }
    }

    private function _edit_order_product() {
        $this->load->model('order/order_product_model');
        return $this->order_product_model->update_batch($this->_OrderProduct);
    }

    private function _workflow () {
        $this->load->library('workflow/workflow');
        $this->_W = $this->workflow->initialize('order');
    }
    private function _edit_delivered ($OrderId, $Delivered) {
        $this->_W->initialize($OrderId, array('delivered' => $Delivered));
        if (!$this->_W->delivered()) {
            $this->Code = EXIT_ERROR;
            $this->Message = $this->_W->get_failue();
            return false;
        }
        return true;
    }

    private function _edit_delivering ($OrderId, $Delivered) {
        $this->_W->initialize($OrderId, array('delivered' => $Delivered));
        if (!$this->_W->delivering()) {
            $this->Code = EXIT_ERROR;
            $this->Message = $this->_W->get_failue();
            return false;
        }
        return true;
    }

    /**
     * 发送钉钉message
     * @param $Ding
     * @return bool|string
     */
    private function _send_dd_msg ($Ding) {
        if (!!($User = $this->_warehouse())) {
            require_once APPPATH . 'third_party/eapp/send_warehouse.php';
            return send(array("msgtype" => 'link', 'link' => array(
                "messageUrl" => "eapp://pages/pick_sheet/pick_sheet",
                "picUrl" => "@lALOACZwe2Rk",
                "title" => $this->session->userdata('truename') . '发货单',
                "text" => $Ding
            )), $User);
        } else {
            return '没有成品库用户可以发送';
        }
    }

    /**
     * 获取成品库成员
     * @return array|bool
     */
    private function _warehouse() {
        $this->load->model('permission/usergroup_model');
        $this->load->model('manage/user_model');
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('成品库'))) {
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
}
