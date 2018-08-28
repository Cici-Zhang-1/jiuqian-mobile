<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Zhangcc
 * @version
 * @des
 * 衣柜拆单
 */

require_once dirname(__FILE__).'/D_abstract.php';

class D_y extends D_abstract{
    private $_CI;
    private $_Failue = '';
    private $_Code = 'y';
    private $_Save;

    public function __construct(){
        $this->_CI = &get_instance();
        parent::__construct($this->_CI);
    }

    public function edit($Save, $Code){
        $this->_Save = $Save;
        $this->_Code = $Code;
        
        $Order = array(
            'oid' => $this->_CI->input->post('oid', true)
        );
        $Order['oid'] = intval(trim($Order['oid']));

        $Set = $this->_CI->input->post('set', true); /*集*/
        $Set = intval(trim($Set));
        $Set = ($Set <= 0) ? 1: ($Set> 60?60:$Set);

        $OrderProduct = array(
            'opid' => $this->_CI->input->post('opid', true),
            'product' => $this->_CI->input->post('product', true),
            'remark' => $this->_CI->input->post('remarks', true)
        );
        $OrderProduct['opid'] = intval(trim($OrderProduct['opid']));

        $WardrobeStruct = array(
            'opwsid' => $this->_CI->input->post('opwsid', true),
            'struct' => $this->_CI->input->post('struct', true)
        );
        $WardrobeStruct['opwsid'] = intval(trim($WardrobeStruct['opwsid']));

        $BoardPlate = $this->_CI->input->post('board_plate', true);

        $Workflow = array(); /*记录工作流*/
        if('dismantled' == $this->_Save && empty($BoardPlate)){
            $this->_Failue = '没有板块, 不能确认衣柜拆单!';
        }else{
            if($OrderProduct['opid'] > 0){
                /**
                 * 订单产品已经建立
                 */
                if(empty($CabinetStruct['opwsid'])){
                    !empty($WardrobeStruct['struct'])
                    && $Opwsid = $this->_add_order_product_wardrobe_struct($WardrobeStruct['struct'], $OrderProduct['opid']);
                }else{
                    !empty($WardrobeStruct['struct'])
                    && $Opwsid = $this->_edit_order_product_wardrobe_struct($WardrobeStruct);
                }
                !empty($BoardPlate)
                && $this->_add_order_product_board_plate($BoardPlate, $OrderProduct['opid']);
                $Opid = $OrderProduct['opid'];
                unset($OrderProduct['opid']);
                $this->_edit_order_product($OrderProduct, $Opid);
            
                $Workflow[] = $Opid;
                unset($Opid);
                if(--$Set >= 1){
                    if(!!($Opids = $this->_add_order_product($Order['oid'], $Set, $OrderProduct['product'], $this->_Code))){
                        foreach ($Opids as $value){
                            if(!empty($WardrobeStruct['struct'])
                                && !!($Opwsid = $this->_add_order_product_wardrobe_struct($WardrobeStruct['struct'], $value))){
                                !empty($BoardPlate)
                                && $this->_add_order_product_board_plate($BoardPlate, $value);
                            }else{
                                break;
                            }
                            $Workflow[] = $value;
                        }
                    }else{
                        $this->_Failue = '新增订单产品失败!';
                    }
                }
            }else{
                /**
                 * 订单产品需要新建
                 */
                if($Order['oid'] > 0){
                    if(!!($Opids = $this->_add_order_product($Order['oid'], $Set, $OrderProduct['product'], $this->_Code))){
                        foreach ($Opids as $value){
                            if(!empty($WardrobeStruct['struct'])
                                && !!($Opwsid = $this->_add_order_product_wardrobe_struct($WardrobeStruct['struct'], $value))){
                                !empty($BoardPlate)
                                && $this->_add_order_product_board_plate($BoardPlate, $value);
                            }else{
                                break;
                            }
                            $Workflow[] = $value;
                        }
                    }
                }else{
                    $this->_Failue .= '请创建订单之后再拆单';
                }
            }
            if(empty($this->_Failue) && !empty($Workflow)){
                $this->_CI->load->library('workflow/workflow');
                if(!!($this->_CI->workflow->initialize('order_product', $Workflow))){
                    $this->_CI->workflow->{$this->_Save}();
                }else{
                    $this->_Failue = $this->_CI->workflow->get_failue();
                }
            }
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
     * 新建订单产品结构
     * @param unknown $Struct
     * @param unknown $Opid
     */
    private function _add_order_product_wardrobe_struct($Struct, $Opid){
        $this->_CI->load->model('order/order_product_wardrobe_struct_model');
        $Struct['opid'] = $Opid;
        if(!!($Opwsid = $this->_CI->order_product_wardrobe_struct_model->insert($Struct))){
            return $Opwsid;
        }else{
            $this->_Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建柜体结构失败!';
            return false;
        }
    }
    /**
     * 编辑订单产品结构
     * @param unknown $CabinetStruct
     * @param unknown $Opid
     */
    private function _edit_order_product_wardrobe_struct($WardrobeStruct){
        $this->_CI->load->model('order/order_product_wardrobe_struct_model');
        $WardrobeStruct = gh_escape($WardrobeStruct);
        if(!!($this->_CI->order_product_wardrobe_struct_model->update($WardrobeStruct['struct'], $WardrobeStruct['opwsid']))){
            return $WardrobeStruct['opwsid'];
        }else{
            $this->_Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'修改柜体结构失败!';
            return false;
        }
    }

    /**
     * 新增订单产品板材板块
     * @param unknown $BoardPlate
     * @param unknown $Opid
     */
    private function _add_order_product_board_plate($BoardPlate, $Opid){
        $this->_CI->load->model('order/order_product_board_model');
        $this->_CI->load->model('order/order_product_board_plate_model');
        $this->_CI->load->helper('dismantle_helper');
        $Opbids = array(); /*已经存在的订单产品板材统计Id号*/
        $Board = array(); /*板块中包含的板材*/
        if($this->_is_valid_board($BoardPlate)){
            foreach ($BoardPlate as $key => $value){
                if(!isset($Board[$value['good']])){
                    $Board[$value['good']] = array(
                        'opid' => $Opid,
                        'board' => $value['good'],
                        'amount' => 1,
                        'area' => $value['area']
                    );
                    if(!($Board[$value['good']]['opbid'] = $this->_CI->order_product_board_model->is_existed($Opid, gh_escape($value['good'])))){
                        /*如果不存在则插入订单产品板材*/
                        $Board[$value['good']] = gh_escape($Board[$value['good']]);
                        $Board[$value['good']]['opbid'] = $this->_CI->order_product_board_model->insert($Board[$value['good']]);
                    }
                    /* }else{ */
                    array_push($Opbids, $Board[$value['good']]['opbid']);
                    /* } */
                }else{
                    $Board[$value['good']]['amount']++;
                    $Board[$value['good']]['area'] += $value['area'];
                }
                $value['opbid'] = $Board[$value['good']]['opbid'];

                $value['thick'] = preg_replace('/^(\d+)(.*)/', '${1}', $value['good']);

                $value = array_merge($value, $this->_get_edge_thick($value));

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
            $this->_CI->order_product_board_plate_model->delete_by_opid($Opid);
            if(!empty($Opbids)){
                $this->_CI->order_product_board_model->delete_not_in($Opid, $Opbids);
            }
            /* if(!empty($Opbids)){
             $this->_CI->order_product_board_plate_model->delete_by_opbid($Opbids)
             && $this->_CI->order_product_board_model->delete_not_in($Opid, $Opbids);
             }else{
             $this->_CI->order_product_board_plate_model->delete_by_opbid($Opid);
             } */
            $BoardPlate = gh_escape($BoardPlate);
            if(!!($this->_CI->order_product_board_plate_model->insert_batch($BoardPlate))
                && !!($this->_CI->order_product_board_model->update_batch($Board))){
                return true;
            }else{
                $this->_Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'保存拆单板块失败!';
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * 判断是否为异形
     * @param unknown $Name
     */
    private function _is_abnormity($Name){
        static $Abnormity = array();
        if(empty($Abnormity)){
            $this->_CI->load->model('data/abnormity_model');
            if(!($Abnormity = $this->_CI->abnormity_model->select_abnormity(1, FALSE))){
                return 0;
            }
        }
        $Flag = 0;
        foreach ($Abnormity as $key => $value){
            if(preg_match('/'.$value['name'].'/', $Name)){
                $Flag = 1;
                break;
            }
        }
        return $Flag;
    }

    private function _get_edge_thick($Value){
        /*if(false === $Value){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 0;
        }elseif('HHHH' == $Value['edge']){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1.5;
        }elseif ('bbbb' == $Value['edge']){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
        }elseif ('Hb' == $Value['edge']){
            $Return['up_edge'] = 1.5;
            $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = 1;
        }elseif ('HHH' == $Value['edge']){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1.5;
        }elseif ('bbb' == $Value['edge']){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
        }elseif ('Hbbb' == $Value['edge']){
            $Return['up_edge'] = 1.5;
            $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = 1;
        }elseif(!!(preg_match("/[\x4e00-\x9fa5]+/", $Value['edge']))){
            $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = 1;
        }else{
            $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = 0;
        }*/
        /*if(false === $Value){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = O_EDGE;
        }elseif('HHHH' == $Value['edge']){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = H_EDGE;
        }elseif('4H' == $Value) {
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = I_EDGE;
        }elseif('3H' == $Value) {
            $Return['left_edge'] = $Return['up_edge'] = $Return['down_edge'] = I_EDGE;
            $Return['right_edge'] = O_EDGE;
        }elseif ('bbbb' == $Value['edge']){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = B_EDGE;
        }elseif ('Hb' == $Value['edge']){
            $Return['up_edge'] = H_EDGE;
            $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = B_EDGE;
        }elseif ('HHH' == $Value['edge']){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = H_EDGE;
        }elseif ('bbb' == $Value['edge']){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = B_EDGE;
        }elseif ('Hbbb' == $Value['edge']){
            $Return['up_edge'] = H_EDGE;
            $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = B_EDGE;
        }elseif(!!(preg_match("/[\x4e00-\x9fa5]+/", $Value['edge']))){
            $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = B_EDGE;
        }else{
            $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = O_EDGE;
        }*/
        $this->_CI->load->model('data/wardrobe_edge_model');
        $Return = array();
        // if (!!($Edges = $this->_CI->wardrobe_edge_model->select_wardrobe_edge_by_name(gh_escape($Value['edge'])))) {
        if (!!($Edges = $this->_CI->wardrobe_edge_model->select_wardrobe_edge())) {
            $EdgeName = gh_escape($Value['edge']);
            foreach ($Edges as $Key => $IValue) {
                if ($IValue['name'] != $EdgeName) {
                    unset($Edges[$Key]);
                }
            }
            if (count($Edges) > 0) {
                $Edge = false;
                foreach ($Edges as $Key => $IValue) {
                    if ($IValue['thick'] == $Value['thick']) {
                        $Edge = $IValue;
                        break;
                    }
                }
                if ($Edge == false) {
                    foreach ($Edges as $Key => $IValue) {
                        if ($IValue['thick'] == 0) {
                            $Edge = $IValue;
                            break;
                        }
                    }
                }
                $Return['up_edge'] = !empty($Edge['ups']) ? $Edge['ups'] : O_EDGE;
                $Return['down_edge'] = !empty($Edge['downs']) ? $Edge['downs'] : O_EDGE;
                $Return['left_edge'] = !empty($Edge['lefts']) ? $Edge['lefts'] : O_EDGE;
                $Return['right_edge'] = !empty($Edge['rights']) ? $Edge['rights'] : O_EDGE;
            } else {
                $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = O_EDGE;
            }
        }else {
            $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = O_EDGE;
        }
        return $Return;
    }
    
    private function _is_valid_board($BoardPlate){
        $this->_CI->load->model('product/board_model');
        $Board = array(); /*板块中包含的板材*/
        foreach ($BoardPlate as $key => $value){
            if(!in_array($value['good'], $Board)){
                if($this->_CI->board_model->select_board_id(gh_escape($value['good']))){
                    $Board[] = $value['good'];
                }else{
                    $this->_Failue = $value['good'].'不在系统中, 请先登记板材!';
                    break;
                }
            }
        }
        if(!empty($this->_Failue)){
            return false;
        }else{
            return true;
        }
    }
    
    public function get_failue(){
        return $this->_Failue;
    }
    
    
    public function read(){

    }
    
    public function remove($Id, $OrderProductNum = ''){
        $this->_CI->load->model('order/order_product_wardrobe_struct_model');
        $this->_CI->load->model('order/order_product_board_plate_model');
        $this->_CI->order_product_wardrobe_struct_model->delete($Id);
        $this->_CI->order_product_board_plate_model->delete_relate($Id, $OrderProductNum);
    }
}