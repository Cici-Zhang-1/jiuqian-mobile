<?php namespace Post_sale;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Administrator
 * @version
 * @des
 */

require_once dirname(__FILE__).'/P_abstract.php';

class P_p extends P_abstract{
    private $_Save;
    private $_W;

    static $_Fitting;
    static $_Fittings;

    public function __construct(){
        parent::__construct();
        $this->_CI->load->model('order/order_product_model');
        $this->_CI->load->model('order/order_product_fitting_model');
        $this->_CI->load->library('workflow/workflow');
        $this->_W = $this->_CI->workflow->initialize('order_product');
    }

    public function edit ($Save, $OrderProduct) {
        $this->_Save = $Save;
        $this->_OrderProductInfo = $OrderProduct;
        $this->_OderProductId = $this->_CI->input->post('v', true);
        $this->_OderProductId = intval(trim($this->_OderProductId));
        $this->_OrderProduct['sum'] = 0;

        self::$_Fitting = $this->_CI->input->post('order_product_board_plate', true);

        if (empty($this->_OderProductId)) {
            $GLOBALS['error'] = '请选择需要确认的订单产品!';
            return false;
        } elseif (empty(self::$_Fitting) || !($this->_check_fitting())) {
            $GLOBALS['error'] = '请添加配件信息!';
            return false;
        } else {
            $this->_edit_order_product();
            $this->_add_order_product_fitting();
            return $this->_workflow();
        }
    }

    /**
     * 复制板块信息
     */
    private function _check_fitting () {
        $Fitting = self::$_Fitting;
        $Status = $this->_get_source_status();
        $MergeFitting = array();
        foreach ($Fitting as $Key => $Value) {
            $Value = array_merge($Value, $Status);
            if (empty($Value['fitting']) || empty($Value['amount'])) {
                unset($Fitting[$Key]);
                continue;
            } else {
                if (!($FittingInfo = $this->_is_valid_fitting($Value['goods_speci_id'], $Value['fitting']))) {
                    $FittingInfo = array(
                        'goods_speci_id' => 0,
                        'purchase_unit' => '--',
                        'purchase' => 0,
                        'unit_price' => 0
                    );
                }
                if (empty($Value['goods_speci_id'])) {
                    $Value['goods_speci_id'] = $FittingInfo['goods_speci_id'];
                }
                if (empty($Value['purchase_unit'])) {
                    $Value['purchase_unit'] = $FittingInfo['purchase_unit'];
                }
                if (empty($Value['purchase'])) {
                    $Value['purchase'] = $FittingInfo['purchase'];
                }
                if (empty($Value['unit_price']) && $Value['unit_price'] != 0) {
                    $Value['unit_price'] = $FittingInfo['unit_price'];
                }
                $Value['amount'] = floatval($Value['amount']);
                $Value['sum'] = ceil($Value['amount'] * $Value['unit_price']); // 计算价格
                $this->_OrderProduct['sum'] += $Value['sum'];
                $Value['order_product_id'] = $this->_OderProductId;
            }
            $MergeFitting[$Key] = $Value;
        }

        if (count($MergeFitting) > 0) {
            self::$_Fitting = array_values($MergeFitting);
            return true;
        } else {
            $GLOBALS['error'] = '请添加板块信息!';
        }
    }

    private function _get_source_status () {
        return $this->_CI->order_product_fitting_model->select_for_post_sale($this->_OderProductId);
    }

    /**
     * 新增橱柜板块清单
     * @param unknown $BoardPlate
     * @param unknown $Opid
     */
    private function _add_order_product_fitting(){
        $Fitting = self::$_Fitting;
        $this->_CI->order_product_fitting_model->delete_by_order_product_id($this->_OderProductId);
        $Fitting = gh_escape($Fitting);
        if(!!($this->_CI->order_product_fitting_model->insert_batch_post($Fitting))){
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

    private function _is_valid_fitting ($GoodsSpeci, $Fitting) {
        if (!isset(self::$_Fittings)) {
            $this->_get_fittings();
        }
        if (isset(self::$_Fittings)) {
            if (isset(self::$_Fittings[$GoodsSpeci])) {
                return self::$_Fittings[$GoodsSpeci];
            } else {
                $GLOBALS['error'] = $Fitting . '不在系统中, 请先登记配件!';
            }
        } else {
            $GLOBALS['error'] = '系统中没有配件信息';
        }
        return false;
    }
    private function _get_fittings () {
        $this->_CI->load->model('product/goods_speci_model');
        if (!!($Fittings = $this->_CI->goods_speci_model->select_by_product_code('P'))) {
            foreach ($Fittings['content'] as $Key => $Value) {
                self::$_Fittings[$Value['v']] = $Value;
            }
            return true;
        }
        return false;
    }
}