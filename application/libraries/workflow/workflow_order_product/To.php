<?php namespace Wop;
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 20:15
 * Des: 订单产品在电子锯和推台锯之间转换
 */
class To {
    private $_CI;
    private $_Source_ids;
    private $_OrderProduct;
    private $_Classify = array();

    private $_ErrorMsg = '';
    public function __construct($SourceIds) {
        $this->_CI = &get_instance();
        $this->_Source_ids = $SourceIds;
    }

    public function get_error_msg () {
        return $this->_ErrorMsg;
    }

    /**
     * 不优化时转换
     * @return bool
     */
    public function to_board () {
        $this->_clear_classify();
        if (!!($this->_OrderProduct = $this->_CI->order_product_model->select_by_v($this->_Source_ids))) {
            $this->_CI->order_product_model->trans_start();
            foreach ($this->_OrderProduct as $Key => $Value) {
                if (!($this->_edit_order_product_board($Value))) {
                    $this->_ErrorMsg = '在设置放到推台锯时出错';
                    return false;
                }
                $this->_OrderProduct[$Key] = $Value;
            }
            $this->_CI->order_product_model->trans_complete();
            if ($this->_CI->order_product_model->trans_status() === FALSE){
                $this->_ErrorMsg = '这次执行操作, 发生未知错误!';
                return false;
            }
            return true;
        } else {
            $this->_ErrorMsg = '您要放到推台锯的订单不存在';
            return false;
        }
    }

    /**
     * 转换到优化
     * @return bool
     */
    public function to_classify () {
        $this->_clear_board();
        $this->_clear_classify();
        if (!!($this->_OrderProduct = $this->_CI->order_product_model->select_by_v($this->_Source_ids))) {
            $this->_CI->order_product_model->trans_start();
            foreach ($this->_OrderProduct as $Key => $Value) {
                if (!($this->_edit_classify($Value['order_product_id']))) {
                    $this->_ErrorMsg = '在设置放到电子锯时出错';
                    return false;
                }
                $this->_OrderProduct[$Key] = $Value;
            }
            $this->_CI->order_product_model->trans_complete();
            if ($this->_CI->order_product_model->trans_status() === FALSE){
                $this->_ErrorMsg = '这次执行操作, 发生未知错误!';
                return false;
            }
            return true;
        } else {
            $this->_ErrorMsg = '您要放到电子锯的订单不存在!';
            return false;
        }
    }

    /**
     * 清除已经建立的板块分类
     */
    private function _clear_classify () {
        $this->_CI->load->model('order/order_product_classify_model');
        $this->_CI->order_product_classify_model->clear($this->_Source_ids);
    }

    private function _clear_board () {
        $this->_CI->load->model('order/order_product_board_model');
        $this->_CI->order_product_board_model->clear($this->_Source_ids);
    }

    /**
     * 编辑订单产品板块工作流
     * @param $OrderProduct
     * @return bool
     */
    private function _edit_order_product_board ($OrderProduct) {
        $this->_CI->load->model('order/order_product_board_model');
        $ProductionLine = $this->_get_product_production_line($OrderProduct['code']);
        if (!!($Board = $this->_CI->order_product_board_model->select_for_sure($OrderProduct['order_product_id']))) {
            foreach ($Board as $Key => $Value) {
                $Board[$Key] = array_merge($Value, $ProductionLine);
            }
            $this->_CI->order_product_board_model->update_batch($Board);
        }
        return true;
    }
    private function _get_product_production_line ($Code) {
        static $Product = array();
        if (empty($Product[$Code])) {
            $this->_CI->load->model('product/product_model');
            $Product[$Code] = $this->_CI->product_model->select_by_code($Code);
        }
        return $this->_get_procedure_workflow($Product[$Code]['production_line']);
    }
    /**
     * 获取工序工作流
     * @param $ProductionLine integer
     * @return array
     */
    private function _get_procedure_workflow ($ProductionLine) {
        static $ProductionLineProcedure;
        if (empty($ProductionLineProcedure)) {
            $this->_CI->load->model('data/production_line_procedure_model');
            if (!!($Query = $this->_CI->production_line_procedure_model->select())) {
                foreach ($Query as $Key => $Value) {
                    $ProductionLineProcedure[$Value['production_line']] = $Value;
                }
            }
        }
        return $ProductionLineProcedure[$ProductionLine];
    }



    /**
     * 更新Qrcode和板块分类
     * @param $OrderProductId
     * @return bool
     */
    private function _edit_classify ($OrderProductId) {
        $this->_CI->load->model('order/order_product_board_plate_model');
        if (!!($Qrcode = $this->_CI->order_product_board_plate_model->select_for_sure($OrderProductId))) {
            $this->_CI->load->model('order/order_product_classify_model');
            foreach ($Qrcode as $Key => $Value) {
                $Value['order_product_classify_id'] = $this->_get_order_product_classify_id($Value);
                $Qrcode[$Key] = $Value;
            }
            return $this->_edit_order_product_board_plate($Qrcode) && $this->_edit_order_product_classify();
        }
        return true;
    }
    private function _edit_order_product_board_plate ($Data) {
        return $this->_CI->order_product_board_plate_model->update_batch($Data);
    }
    private function _edit_order_product_classify(){
        $this->_CI->load->model('order/order_product_classify_model');
        if (!empty($this->_Classify)) {
            return $this->_CI->order_product_classify_model->update_batch($this->_Classify);
        }
        return true;
    }

    /**
     * 获取订单产品板块分类ID
     * @param $BoardPlate
     * @return mixed
     */
    private function _get_order_product_classify_id ($BoardPlate) {
        $Classify = $this->_get_classify($BoardPlate);
        $Classify['board'] = $BoardPlate['board'];
        $Classify['order_product_id'] = $BoardPlate['order_product_id'];
        $Key = implode('', $Classify);
        if (!isset($this->_Classify[$Key])) {
            $this->_Classify[$Key] = $Classify;
            $this->_Classify[$Key]['amount'] = $BoardPlate['amount'];
            $this->_Classify[$Key]['area'] = $BoardPlate['area'];

            if(!($this->_Classify[$Key]['v'] = $this->_CI->order_product_classify_model->is_exist($Classify))){
                $this->_Classify[$Key]['v'] = $this->_CI->order_product_classify_model->insert($Classify);
            }
        } else {
            $this->_Classify[$Key]['amount'] += $BoardPlate['amount'];
            $this->_Classify[$Key]['area'] += $BoardPlate['area'];
        }
        return $this->_Classify[$Key]['v'];
    }

    /**
     * 板块分类信息
     * @param $Data
     * @return array
     */
    private function _get_classify($Data){
        static $Classify;
        static $Standard;
        if(empty($Classify)){
            $this->_CI->load->model('data/classify_model');
            $Classify = $this->_CI->classify_model->select_children();
            $Standard = $this->_CI->classify_model->select_by_name('标准板块');
        }
        $Return = array(
            'classify_id' => $Standard['v'],
            'production_line' => $Standard['production_line'] // 生产线
        );
        $Parent = ZERO;
        $ProductionLine = ZERO;
        $Flag = true;
        if (!empty($Classify)) {
            foreach ($Classify as $key => $value){
                if($value['plate'] != '' && $value['plate'] != $Data['plate_name']){
                    $Flag = false;
                }
                if($value['width_min'] < $value['width_max'] && $value['length_min'] < $value['length_max']){   /*Length + Width*/
                    if(!(($Data['width'] >= $value['width_min'] && $Data['width'] < $value['width_max']) ||
                        ($Data['length'] >= $value['length_min'] && $Data['length'] < $value['length_max']))){
                        $Flag = false;
                    }
                }elseif ($value['width_min'] < $value['width_max'] && $value['length_min'] == $value['length_max']){    /*Width*/
                    if(!($Data['width'] >= $value['width_min'] && $Data['width'] < $value['width_max'])){
                        $Flag = false;
                    }
                }elseif ($value['width_min'] == $value['width_max'] && $value['length_min'] < $value['length_max']){    /*Length*/
                    if(!($Data['length'] >= $value['length_min'] && $Data['length'] < $value['length_max'])){
                        $Flag = false;
                    }
                }

                if($value['thick'] != 0 && $value['thick'] != $Data['thick']){
                    $Flag = false;
                }
                if($value['edge'] != '' && $value['edge'] != $Data['edge']){
                    $Flag = false;
                }
                if($value['slot'] != '' && $value['slot'] != $Data['slot']){
                    $Flag = false;
                }
                if ($value['decide_size'] != '' && $value['decide_size'] != $Data['decide_size']) {
                    $Flag = false;
                }
                if ($value['abnormity'] != ZERO && $value['abnormity'] != $Data['abnormity']) {
                    $Flag = false;
                }
                if($value['remark'] != '' && !(preg_match('/'.$value['remark'].'/', $Data['remark']))){
                    $Flag = false;
                }
                if(true == $Flag){
                    $Parent = $value['parent'];
                    $ProductionLine = $value['production_line'];
                    break;
                } else {
                    $Flag = true;
                }
            }
        }
        if (!empty($Parent)) {
            $Return['classify_id'] = $Parent;
            $Return = array_merge($Return, $this->_get_procedure_workflow($ProductionLine));
        } else {
            $Return = array_merge($Return, $this->_get_procedure_workflow($Return['production_line']));
        }
        return $Return;
    }
}