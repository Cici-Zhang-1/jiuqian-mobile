<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月13日
 * @author Zhangcc
 * @version
 * @des
 * 橱柜拆单
 */


require_once dirname(__FILE__).'/D_abstract.php';

class D_w extends D_abstract{
    private $_Save;
    private $_W;

    public function __construct(){
        $this->_CI = &get_instance();
        parent::__construct($this->_CI);
        $this->_CI->load->model('order/order_product_model');
        $this->_CI->load->model('order/order_product_cabinet_struct_model');
        $this->_CI->load->model('order/order_product_board_model');
        $this->_CI->load->model('order/order_product_board_plate_model');
        $this->_CI->load->library('workflow/workflow');
        $this->_W = $this->_CI->workflow->initialize('order_product');
    }
    
    public function edit($Save){
        $this->_Save = $Save;
        $Order = array(
            'order_id' => $this->_CI->input->post('order_id', true)
        );
        $Order['order_id'] = intval(trim($Order['order_id']));

        $OrderProductId = $this->_CI->input->post('v', true);
        $OrderProduct = array(
            'product' => $this->_CI->input->post('product', true)
        );
        $OrderProductId = intval(trim($OrderProductId));
        
        $CabinetStruct = $this->_CI->input->post('struct', true);
        $CabinetStruct['v'] = intval(trim($CabinetStruct['v']));
        
        $BoardPlate = $this->_CI->input->post('order_product_board_plate', true);

        if (empty($OrderProductId) || empty($BoardPlate)) {
            $this->_Failue = '没有板块, 不能确认橱柜拆单!';
            return false;
        } else {
            /**
             * 订单产品已经建立
             */
            if(empty($CabinetStruct['v'])){
                //添加橱柜贵体结构
                $CabinetStruct['order_product_id'] = $OrderProductId;
                $this->_CI->order_product_cabinet_struct_model->insert($CabinetStruct);
            }else{
                //修改橱柜柜体结构
                $V = $CabinetStruct['v'];
                unset($CabinetStruct['v']);
                $this->_CI->order_product_cabinet_struct_model->update($CabinetStruct, $V);
            }

            $this->_edit_order_product($OrderProduct, $OrderProductId);

            $this->_add_order_product_board_plate($BoardPlate, $OrderProductId);

            if(empty($this->_Failue)){
                if(!!($this->_W->initialize($OrderProductId))){
                    $this->_W->{$this->_Save}();
                    return true;
                }else{
                    $this->_Failue = $this->_W->get_failue();
                }
            }
            return false;
        }
    }

    private function _check($BoardPlate) {
        $Return = true;
        if ($this->_Save == 'dismantled') {
            foreach ($BoardPlate as $Key => $Value) {
                if ($Value['qrcode'] != '' && $Value['bd_file'] == '') {
                    $this->_Failue .= $Value['qrcode'] . '板块没有BD文件';
                    $Return = false;
                }
            }
        }
        return $Return;
    }

    /**
     * 新增橱柜板块清单
     * @param unknown $BoardPlate
     * @param unknown $Opid
     */
    private function _add_order_product_board_plate($BoardPlate, $OrderProductId){
        $this->_CI->load->helper('dismantle_helper');
        $Opbids = array(); /*已经存在的订单产品板材统计Id号*/
        $Board = array(); /*板块中包含的板材*/
        foreach ($BoardPlate as $key => $value){
            if ($value['board'] == '' || $value['plate_name'] == '' || empty($value['length']) || empty($value['width'])) {
                unset($BoardPlate[$key]);
                continue;
            } elseif (!($BoardInfo = $this->_is_valid_board($value['board']))) {
                return false;
            }
            if(!isset($Board[$value['board']])){
                $Board[$value['board']] = array(
                    'order_product_id' => $OrderProductId,
                    'board' => $value['board'],
                    'unit_price' => $BoardInfo['saler_unit_price'],
                    'amount' => 1,
                    'area' => $value['area']
                );
                if(!($Board[$value['board']]['order_product_board_id'] = $this->_CI->order_product_board_model->is_existed($OrderProductId, gh_escape($value['board'])))){
                    /*如果不存在则插入订单产品板材*/
                    log_message('debug', $value['board']);
                    $Board[$value['board']] = gh_escape($Board[$value['board']]);
                    $Board[$value['board']]['order_product_board_id'] = $this->_CI->order_product_board_model->insert($Board[$value['board']]);
                }
                array_push($Opbids, $Board[$value['board']]['order_product_board_id']);
            }else{
                $Board[$value['board']]['amount']++;
                $Board[$value['board']]['area'] += $value['area'];
            }
            $value['order_product_board_id'] = $Board[$value['board']]['order_product_board_id'];
            $value['thick'] = $BoardInfo['thick'];

            $value = array_merge($value, $this->_get_edge_thick($value)); // 封边信息

            if(empty($value['qrcode'])){
                $value['qrcode'] = null;
            }
            if(isset($value['remark']) && '' != $value['remark']){
                $value['abnormity'] = $this->_is_abnormity($value['remark']);
            }else{
                $value['abnormity'] = 0;
            }
            $BoardPlate[$key] = $value;
        }
        $this->_CI->order_product_board_plate_model->delete_by_order_product_id($OrderProductId);
        if(!empty($Opbids)){
            $this->_CI->order_product_board_model->delete_not_in($OrderProductId, $Opbids);
        }
        $BoardPlate = gh_escape($BoardPlate);
        if(!!($this->_CI->order_product_board_plate_model->insert_batch($BoardPlate))
            && !!($this->_CI->order_product_board_model->update_batch($Board))){
            return true;
        }else{
            $this->_Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'保存拆单板块失败!';
            return false;
        }
    }
    public function get_failue(){
        return $this->_Failue;
    }
    public function read(){
        
    }
    
    public function remove($Id, $OrderProductNum = ''){
        return $this->_CI->order_product_board_model->delete_by_order_product_id($Id);
    }
}