<?php namespace Dismantle;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Zhangcc
 * @version
 * @des
 */

require_once dirname(__FILE__).'/D_abstract.php';

class D_g extends D_abstract{
    private $_Save;
    private $_W;

    private $_OrderProductNum;
    static $_Other;
    static $_Others;

    public function __construct(){
        parent::__construct();
        $this->_CI->load->model('order/order_product_model');
        $this->_CI->load->model('order/order_product_other_model');
        $this->_CI->load->library('workflow/workflow');
        $this->_W = $this->_CI->workflow->initialize('order_product');
    }

    public function edit ($Save) {
        $this->_Save = $Save;
        $this->_OderProductId = $this->_CI->input->post('v', true);
        $this->_OderProductId = intval(trim($this->_OderProductId));
        $this->_OrderProduct['product'] = $this->_CI->input->post('product', true);
        $this->_OrderProduct['remark'] = $this->_CI->input->post('remark', true);

        self::$_Other = $this->_CI->input->post('order_product_board_plate', true);

        if (empty($this->_OderProductId)) {
            $GLOBALS['error'] = '请选择需要确认的订单产品!';
            return false;
        } elseif (empty(self::$_Other) || !($this->_check_other())) {
            $GLOBALS['error'] = '请添加外购信息!';
            return false;
        } else {
            $this->_edit_order_product();
            $this->_add_order_product_other();
            return $this->_workflow();
        }
    }

    /**
     * 复制板块信息
     */
    private function _check_other () {
        $Other = self::$_Other;
        // $MergeOther = array();
        foreach ($Other as $Key => $Value) {
            /*if (empty($Value['other']) || empty($Value['goods_speci_id']) || empty($Value['amount'])) {
                unset($Other[$Key]);
                continue;
            } else if (!($OtherInfo = $this->_is_valid_other($Value['goods_speci_id'], $Value['other']))) {
                return false;
            } else {
                $Value['purchase_unit'] = $OtherInfo['purchase_unit'];
                $Value['purchase'] = $OtherInfo['purchase'];
                $Value['unit_price'] = $OtherInfo['saler_unit_price'];
                $Value['amount'] = floatval($Value['amount']);
                $Value['sum'] = ceil(($Value['amount'] * $Value['unit_price']) * M_REGULAR) / M_REGULAR; // 计算价格
                $Value['order_product_id'] = $this->_OderProductId;
            }*/
            if (empty($Value['other']) || empty($Value['amount'])) {
                unset($Other[$Key]);
                continue;
            } else {
                if (!($OtherInfo = $this->_is_valid_other($Value['goods_speci_id'], $Value['other']))) {
                    $Value['purchase_unit'] = '--';
                    $Value['purchase'] = 0;
                    $Value['unit_price'] = 0;
                } else {
                    $Value['purchase_unit'] = $OtherInfo['purchase_unit'];
                    $Value['purchase'] = $OtherInfo['purchase'];
                    $Value['unit_price'] = $OtherInfo['saler_unit_price'];
                }
                $Value['amount'] = floatval($Value['amount']);
                $Value['sum'] = ceil($Value['amount'] * $Value['unit_price']); // 计算价格
                $Value['order_product_id'] = $this->_OderProductId;
            }
            $Other[$Key] = $Value;
        }

        if (count($Other) > 0) {
            self::$_Other = array_values($Other);
            return true;
        } else {
            $GLOBALS['error'] = '请添加板块信息!';
        }
    }

    /**
     * 新增橱柜板块清单
     * @param unknown $BoardPlate
     * @param unknown $Opid
     */
    private function _add_order_product_other(){
        $Other = self::$_Other;
        $this->_CI->order_product_other_model->delete_by_order_product_id($this->_OderProductId);
        $Other = gh_escape($Other);
        if(!!($this->_CI->order_product_other_model->insert_batch($Other))){
            return true;
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'保存拆单配件失败!';
            return false;
        }
    }

    /**
     * 复制订单
     */
    public function repeat ($To, $From) {
        $this->_Save = 'dismantling';
        $this->_OderProductId = $To['v'];
        $this->_OrderProductNum = $To['order_product_num'];
        $this->_OrderProduct['product'] = $From['product'];
        $this->_OrderProduct['remark'] = $From['order_product_remark'];
        $this->_edit_order_product();
        $this->_get_other($From);

        if (!empty(self::$_Other)) {
            $this->_add_order_product_other();
        }
        return $this->_workflow();
    }


    /**
     * 获取板块清单
     * @param $OrderProductId
     */
    private function _get_other ($OrderProductId) {
        if (empty(self::$_Other)) {
            if (!!($Query = $this->_CI->order_product_other_model->select_by_order_product_id(array('order_product_id' => $OrderProductId)))) {
                $Other = $Query['content'];
                unset($Query);
                foreach ($Other as $Key => $Value) {
                    $Other[$Key]['order_product_id'] = $this->_OderProductId;
                }
                self::$_Other = $Other;
            } else {
                return false;
            }
        }
        return true;
    }

    private function _workflow() {
        if(empty($GLOBALS['error'])){
            if(!!($this->_W->initialize($this->_OderProductId))){
                $this->_W->{$this->_Save}();
                return true;
            }else{
                $GLOBALS['error'] = $this->_W->get_failue();
            }
        }
        return false;
    }
    public function read(){

    }

    public function remove($Id, $OrderProductNum = ''){
        return $this->_CI->order_product_other_model->delete_by_order_product_id($Id);
    }

    private function _is_valid_other ($GoodsSpeci, $Other) {
        if (!isset(self::$_Others)) {
            $this->_get_others();
        }
        if (isset(self::$_Others)) {
            if (isset(self::$_Others[$GoodsSpeci])) {
                return self::$_Others[$GoodsSpeci];
            } else {
                // $GLOBALS['error'] = $Other . '不在系统中, 请先登记配件!';
            }
        } else {
            // $GLOBALS['error'] = '系统中没有配件信息';
        }
        return false;
    }
    private function _get_others () {
        $this->_CI->load->model('product/goods_speci_model');
        if (!!($Others = $this->_CI->goods_speci_model->select_by_product_code('G'))) {
            foreach ($Others['content'] as $Key => $Value) {
                self::$_Others[$Value['v']] = $Value;
            }
            return true;
        }
        return false;
    }
    /**
     * 确认拆单
     * @param $OrderProductId
     * @return bool
     */
    public function dismantled ($OrderProductId) {
        $this->_Save = 'dismantled';
        $this->_OderProductId = $OrderProductId;
        return $this->_workflow();
    }
}