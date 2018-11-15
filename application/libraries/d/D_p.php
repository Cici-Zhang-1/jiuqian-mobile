<?php namespace Dismantle;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Administrator
 * @version
 * @des
 */

require_once dirname(__FILE__).'/D_abstract.php';

class D_p extends D_abstract{
    private $_Save;
    private $_W;

    private $_OrderProductNum;
    static $_Fitting;
    static $_Fittings;

    public function __construct(){
        parent::__construct();
        $this->_CI->load->model('order/order_product_model');
        $this->_CI->load->model('order/order_product_fitting_model');
        $this->_CI->load->library('workflow/workflow');
        $this->_W = $this->_CI->workflow->initialize('order_product');
    }

    public function edit ($Save) {
        $this->_Save = $Save;
        $this->_OderProductId = $this->_CI->input->post('v', true);
        $this->_OderProductId = intval(trim($this->_OderProductId));
        $this->_OrderProduct['product'] = $this->_CI->input->post('product', true);
        $this->_OrderProduct['remark'] = $this->_CI->input->post('remark', true);

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
        $MergeFitting = array();
        foreach ($Fitting as $Key => $Value) {
            // if ((empty($Value['fitting']) && empty($Value['goods_speci_id'])) || empty($Value['amount'])) {
            if (empty($Value['fitting']) || empty($Value['amount'])) {
                unset($Fitting[$Key]);
                continue;
            } elseif (empty($Value['goods_speci_id'])) {
                $this->_CI->load->model('product/goods_speci_model');
                if (!($FittingInfo = $this->_CI->goods_speci_model->is_valid_goods_speci($Value['fitting'], $Value['speci'], $Value['unit']))) {
                    $FittingInfo['purchase'] = 0;
                    $FittingInfo['saler_unit_price'] = 0;
                    $FittingInfo['purchase_unit'] = $Value['unit'];
                    $Value['goods_speci_id'] = 0;
                    /* unset($Fitting[$Key]);
                    continue; */
                } else {
                    $Value['goods_speci_id'] = $FittingInfo['v'];
                    $Value['speci'] = empty($Value['speci']) ? $FittingInfo['speci'] : $Value['speci'];
                    $Value['unit'] = empty($Value['unit']) ? $FittingInfo['unit'] : $Value['unit'];
                }
            } elseif (!($FittingInfo = $this->_is_valid_fitting($Value['goods_speci_id'], $Value['fitting']))) {
                return false;
            }
            $Value['purchase_unit'] = $FittingInfo['purchase_unit'];
            $Value['purchase'] = $FittingInfo['purchase'];
            $Value['unit_price'] = $FittingInfo['saler_unit_price'];
            $Value['amount'] = floatval($Value['amount']);
            $Value['order_product_id'] = $this->_OderProductId;
            $Value['sum'] = ceil($Value['amount'] * $Value['unit_price']);
            $MergeFitting[$Key] = $Value;
            /*if (isset($MergeFitting[$FittingInfo['v']])) {
                $MergeFitting[$FittingInfo['v']]['amount'] += $Value['amount'];
                $MergeFitting[$FittingInfo['v']]['sum'] += $Value['sum'];
            } else {
                $MergeFitting[$FittingInfo['v']] = $Value;
            }*/
        }

        if (count($MergeFitting) > 0) {
            self::$_Fitting = array_values($MergeFitting);
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
    private function _add_order_product_fitting(){
        $Fitting = self::$_Fitting;

        $this->_CI->order_product_fitting_model->delete_by_order_product_id($this->_OderProductId);
        $Fitting = gh_escape($Fitting);
        if(!!($this->_CI->order_product_fitting_model->insert_batch($Fitting))){
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
        $this->_get_fitting($From);

        if (!empty(self::$_Fitting)) {
            $this->_add_order_product_fitting();
        }
        return $this->_workflow();
    }


    /**
     * 获取板块清单
     * @param $OrderProductId
     */
    private function _get_fitting ($OrderProductId) {
        if (empty(self::$_Fitting)) {
            if (!!($Query = $this->_CI->order_product_fitting_model->select_by_order_product_id(array('order_product_id' => $OrderProductId)))) {
                $Fitting = $Query['content'];
                unset($Query);
                foreach ($Fitting as $Key => $Value) {
                    $Fitting[$Key]['order_product_id'] = $this->_OderProductId;
                }
                self::$_Fitting = $Fitting;
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
        return $this->_CI->order_product_fitting_model->delete_by_order_product_id($Id);
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