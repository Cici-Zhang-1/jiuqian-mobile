<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * { title | title | replace({'_': ' '}) } Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Warehouse_order_product extends MY_Controller {
    private $__Search = array(
        'v' => 0,
        'status' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller  __construct Start!');
        $this->load->model('warehouse/warehouse_order_product_model');
        $this->load->model('warehouse/warehouse_model');
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->warehouse_order_product_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $Data['query']['warehouse_v'] = $this->_Search['v'];
        $this->_ajax_return($Data);
    }

    /**
     * 入库，一次入一个订单，但是可以进多个库
     * @return void
     */
    public function in() {
        $WarehouseV = $this->input->post('warehouse_v');
        if (!is_array($WarehouseV)) {
            $_POST['warehouse_v'] = explode(',', $WarehouseV);
        }
        $WarehouseVHand = $this->input->post('warehouse_v_hand');
        if (!is_array($WarehouseVHand)) {
            $_POST['warehouse_v_hand'] = explode(',', $WarehouseVHand);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $WarehouseV = array_merge($Post['warehouse_v'], $Post['warehouse_v_hand']);
            if (!!($WarehouseV = $this->warehouse_model->is_exist($WarehouseV))) {  // 判断库位是否可用
                $Data = array();
                $ChangedWarehouseV = array();
// $PubUrl = pub_url($Data['order_product_num'] . '-' . $Pack . '-' . $I . '-' . $Classify);
                $this->load->model('order/order_product_model');
                if (preg_match(REG_PACK_LABEL_UNSTRICT, $Post['order_product_num'], $Matches)
                    && !!($OrderProduct = $this->order_product_model->is_exist($Matches[1]))) { // 需要入库的订单信息
                    $OrderProductWarehouseInned = json_decode($OrderProduct['order_product_warehouse_num'], true); // 订单产品所在库位
                    $WarehouseInned = json_decode($OrderProduct['warehouse_v'], true);  // 订单所在库位
                    if (is_array($OrderProductWarehouseInned)) {
                        foreach ($OrderProductWarehouseInned as $Key => $Value) {
                            $OrderProductWarehouseInned[$Key] = $Value['v'];
                        }
                    } else {
                        $OrderProductWarehouseInned = array();
                    }
                    // $WarehouseInnedTmp = array();
                    if (is_array($WarehouseInned)) {
                        foreach ($WarehouseInned as $Key => $Value) {
                            $WarehouseInned[$Key] = $Value['v'];
                            // array_push($WarehouseInnedTmp, $Value['v']);
                        }
                    } else {
                        $WarehouseInned = array();
                    }

                    $Classify = isset($Matches[6]) ? ($Matches[6] == 'thick' ? '柜体' : ($Matches[6] == 'thin' ? '背板' : $Matches[6])) : '';
                    foreach ($WarehouseV as $Key => $Value) {
                        $Data[] = array(
                            'warehouse_v' => $Value['v'],
                            'order_product_num' => $Matches[1],
                            'order_num' => $Matches[2],
                            'amount' => isset($Matches[4]) ? $Matches[4] : 0,
                            'classify' => $Classify
                        );
                        if (!in_array($Value['v'], $OrderProductWarehouseInned)) {  // 如果不在已入库的库位中，则新增插入
                            array_push($ChangedWarehouseV, $Value['v']);
                            array_push($OrderProductWarehouseInned, $WarehouseV[$Key]['v']);
                            if (!in_array($Value['v'], $WarehouseInned)) { // 如果是新增且没有其他订单产品编号使用过，则加入到产品订单推荐中
                                array_push($WarehouseInned, $WarehouseV[$Key]['v']);
                            }
                        } else {    // 如果在则不用新增插入
                            unset($WarehouseV[$Key]);
                        }
                    }
                    if (empty($Data)) {
                        $this->Message = $Post['order_product_num'] . '已经入库';
                    } else {
                        if(!!($NewId = $this->warehouse_order_product_model->insert_ignore_batch($Data))) {
                            if (count($ChangedWarehouseV) > 0) {
                                $this->warehouse_model->update(array('status' => 2), $ChangedWarehouseV); // 跟新库位状态
                            }
                            $this->load->model('order/order_model');
                            $this->order_model->update(array('warehouse_v' => $this->_encode_warehouse($WarehouseInned)), $OrderProduct['order_v']); // 更新订单库位状态
                            $this->load->model('order/order_product_model');
                            $this->order_product_model->update(array('warehouse_v' => $this->_encode_warehouse($OrderProductWarehouseInned)), $OrderProduct['v']); // 更新订单产品库位状态
                            $this->Message = '入库成功, 刷新后生效!';
                        }else{
                            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                            $this->Code = EXIT_ERROR;
                        }
                    }
                } else {
                    $this->Message = $Post['order_product_num'] . '不符合入库条件';
                    $this->Code = EXIT_ERROR;
                }
            } else {
                $this->Code = 1;
                $this->Message = '请选择库位';
            }
        }
        $this->_ajax_return();
    }

    private function _encode_warehouse ($Warehouse) {
        $Return = array();
        foreach ($Warehouse as $Key => $Value) {
            array_push($Return, array(
                'num' => $Value,
                'v' => $Value,
            ));
        }
        return json_encode($Return);
    }
    /**
    * 多个订单同时出库
    * @return void
    */
    public function out() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            $Data = array(
                'picker' => $this->session->userdata('uid'),
                'pick_datetime' => date('Y-m-d H:i:s')
            );

            if (!!($In = $this->_in($Where))) {
                if(!!($this->warehouse_order_product_model->update($Data, $In['v']))){
                    $this->_out($In['warehouse_v']);
                    $this->Message = '内容修改成功, 刷新后生效!';
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '所有订单已经出库';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 移库，可以一次移动多个订单，但是只能进入一个仓库
     * @param  int $id
     * @return void
     */
    public function move() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            unset($Post['v']);
            $Where = $this->input->post('v', true);

            if (!!($In = $this->_in($Where))) { //判断是否在库内，还未出库
                if ($this->warehouse_model->is_exist($Post['to'])) {
                    if(!!($this->warehouse_order_product_model->update_move(array('warehouse_v' => $Post['to']), $In['v']))){ //移动到对应库位
                        $this->_out($In['warehouse_v']);
                        $this->warehouse_model->update(array('status' => 2), $Post['to']);
                        $this->_order($In['order_num']);
                        $this->_order_product($In['order_product_num']);
                        $this->Message = '移库成功, 刷新后生效!';
                    }else{
                        $this->Code = EXIT_ERROR;
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'移库失败';
                    }
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '您要移入的库位不存在';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '所有订单已经出库';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 判断哪些是还未出库的订单
     * @param $V
     * @return array|bool
     */
    private function _in($V) {
        if (!!($In = $this->warehouse_order_product_model->is_in($V))) { //判断是否在库内，还未出库
            $Return = array(
                'v' => array(),
                'warehouse_v' => array(),
                'order_product_num' => array(),
                'order_num' => array()
            );
            foreach ($In as $Key => $Value) {
                array_push($Return['v'], $Value['v']); // 可以移库的库位订单产品编号
                if (!in_array($Value['warehouse_v'], $Return['warehouse_v'])) {
                    array_push($Return['warehouse_v'], $Value['warehouse_v']); // 可以移库的库位
                }
                if (!in_array($Value['order_product_num'], $Return['warehouse_v'])) {
                    array_push($Return['order_product_num'], $Value['order_product_num']);
                }
                if (!in_array($Value['order_num'], $Return['order_num'])) {
                    array_push($Return['order_num'], $Value['order_num']);
                }
            }
            return $Return;
        }
        return false;
    }

    /**
     * 将已经清空的库位修改库位状态
     * @param $WarehouseV
     * @return bool
     */
    private function _out($WarehouseV) {
        if (!!($IsNotOut = $this->warehouse_order_product_model->is_not_out($WarehouseV))) {
            foreach ($IsNotOut as $Value) {
                $WarehouseV = array_diff($WarehouseV, [$Value['warehouse_v']]);
            }
            if (count($WarehouseV) > 0) {
                $this->warehouse_model->update(array('status' => 1), $WarehouseV);
            }
        }
        return true;
    }

    /**
     * 更改订单产品库位
     * @param $OrderProductNum
     * @return bool
     */
    private function _order_product ($OrderProductNum) {
        if (!!($OrderProductInned = $this->warehouse_order_product_model->select_order_product_inned($OrderProductNum))) {
            $Data = array();
            foreach ($OrderProductInned as $Key => $Value) {
                if (!isset($Data[$Value['order_product_v']])) {
                    $Data[$Value['order_product_v']] = array(
                        'v' => $Value['order_product_v'],
                        'warehouse_v' => array(
                            $Value['warehouse_v']
                        )
                    );
                } else {
                    if (!in_array($Value['warehouse_v'], $Data[$Value['order_product_v']]['warehouse_v'])) {
                        array_push($Data[$Value['order_product_v']]['warehouse_v'], $Value['warehouse_v']);
                    }
                }
            }
            if (count($Data) > 0) {
                foreach ($Data as $Key => $Value) {
                    $Data[$Key]['warehouse_v'] = $this->_encode_warehouse($Value['warehouse_v']);
                }
                $this->load->model('order/order_product_model');
                $this->order_product_model->update_batch($Data);
            }
        }
        return true;
    }

    /**
     * 移库要更改订单库位
     * @param $OrderNum
     * @return bool
     */
    private function _order ($OrderNum) {
        if (!!($OrderInned = $this->warehouse_order_product_model->select_order_inned($OrderNum))) {
            $Data = array();
            foreach ($OrderInned as $Key => $Value) {
                if (!isset($Data[$Value['order_v']])) {
                    $Data[$Value['order_v']] = array(
                        'v' => $Value['order_v'],
                        'warehouse_v' => array()
                    );
                }
                if (!in_array($Value['warehouse_v'], $Data[$Value['order_v']]['warehouse_v'])) {
                    array_push($Data[$Value['order_v']]['warehouse_v'], $Value['warehouse_v']);
                }
            }
            if (count($Data) > 0) {
                foreach ($Data as $Key => $Value) {
                    $Data[$Key]['warehouse_v'] = $this->_encode_warehouse($Value['warehouse_v']);
                }
                $this->load->model('order/order_model');
                $this->order_model->update_batch($Data);
            }
        }
        return true;
    }
}
