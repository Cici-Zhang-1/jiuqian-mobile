<?php namespace Post_sale;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Zhangcc
 * @version
 * @des
 */

require_once dirname(__FILE__).'/P_abstract.php';

class P_g extends P_abstract{
    private $_Save;
    private $_W;

    static $_Other;
    static $_Others;

    public function __construct(){
        parent::__construct();
        $this->_CI->load->model('order/order_product_model');
        $this->_CI->load->model('order/order_product_other_model');
        $this->_CI->load->library('workflow/workflow');
        $this->_W = $this->_CI->workflow->initialize('order_product');
    }

    public function edit ($Save, $OrderProduct) {
        $this->_Save = $Save;
        $this->_OrderProductInfo = $OrderProduct;
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
        $Status = $this->_get_source_status();
        // $MergeOther = array();
        foreach ($Other as $Key => $Value) {
            $Value = array_merge($Value, $Status);
            if (empty($Value['other']) || empty($Value['amount'])) {
                unset($Other[$Key]);
                continue;
            } else {
                if (!($OtherInfo = $this->_is_valid_other($Value['goods_speci_id'], $Value['other']))) {
                    $OtherInfo = array(
                        'goods_speci_id' => 0,
                        'purchase_unit' => '--',
                        'purchase' => 0,
                        'unit_price' => 0
                    );
                }
                if (empty($Value['goods_speci_id'])) {
                    $Value['goods_speci_id'] = $OtherInfo['goods_speci_id'];
                }
                if (empty($Value['purchase_unit'])) {
                    $Value['purchase_unit'] = $OtherInfo['purchase_unit'];
                }
                if (empty($Value['purchase'])) {
                    $Value['purchase'] = $OtherInfo['purchase'];
                }
                if (empty($Value['unit_price']) && $Value['unit_price'] != 0) {
                    $Value['unit_price'] = $OtherInfo['unit_price'];
                }
                $Value['amount'] = floatval($Value['amount']);
                $Value['sum'] = ceil($Value['amount'] * $Value['unit_price']); // 计算价格
                $this->_OrderProduct['sum'] += $Value['sum'];
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

    private function _get_source_status () {
        return $this->_CI->order_product_other_model->select_for_post_sale($this->_OderProductId);
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
        if(!!($this->_CI->order_product_other_model->insert_batch_post($Other))){
            return true;
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'保存拆单配件失败!';
            return false;
        }
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
}