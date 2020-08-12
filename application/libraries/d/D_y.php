<?php namespace Dismantle;
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
    private $_Save;
    private $_W;

    private $_OrderProductNum;
    static $_WardrobeStruct;
    private $_WardrobeStructId;
    static $_BoardPlate;

    public function __construct(){
        parent::__construct();
        $this->_CI->load->model('order/order_product_model');
        $this->_CI->load->model('order/order_product_wardrobe_struct_model');
        $this->_CI->load->model('order/order_product_board_model');
        $this->_CI->load->model('order/order_product_board_plate_model');
        $this->_CI->load->library('workflow/workflow');
        $this->_W = $this->_CI->workflow->initialize('order_product');
    }

    public function edit ($Save) {
        $this->_Save = $Save;
        $this->_OderProductId = $this->_CI->input->post('v', true);
        $this->_OderProductId = intval(trim($this->_OderProductId));
        $this->_OrderProduct['product'] = $this->_CI->input->post('product', true);
        $this->_OrderProduct['remark'] = $this->_CI->input->post('remark', true);
        $this->_OrderProduct['bd'] = $this->_CI->input->post('bd', true);
        if (empty($this->_OrderProduct['bd'])) {
            $this->_OrderProduct['bd'] = NO;
        }

        self::$_WardrobeStruct = $this->_CI->input->post('struct', true);
        if (!empty(self::$_WardrobeStruct)) {
            self::$_WardrobeStruct = gh_escape(self::$_WardrobeStruct);
            self::$_WardrobeStruct['order_product_id'] = $this->_OderProductId;
            $this->_WardrobeStructId = intval(trim(self::$_WardrobeStruct['v']));
            unset(self::$_WardrobeStruct['v']);
        }

        self::$_BoardPlate = $this->_CI->input->post('order_product_board_plate', true);

        if (empty($this->_OderProductId)) {
            $GLOBALS['error'] = '请选择需要确认的订单产品!';
            return false;
        } elseif (empty(self::$_BoardPlate) || !($this->_check_board_plate())) {
            return false;
        } else {
            $this->_edit_order_product();
            if (!empty(self::$_WardrobeStruct)) {
                $this->_edit_order_product_wardrobe_struct();
            }
            $this->_add_order_product_board_plate();
            return $this->_workflow();
        }
    }

    /**
     * 复制板块信息
     */
    private function _check_board_plate () {
        $BoardPlate = self::$_BoardPlate;
        $NoQrcode = array();
        $Qrcode = array();
        $MaxNum = 0;
        $Prefix = '';
        foreach ($BoardPlate as $Key => $Value) {
            $Value['board'] = trim($Value['board']);
            if ($Value['board'] == '' || $Value['plate_name'] == '' || empty($Value['length']) || empty($Value['width'])) {
                unset($BoardPlate[$Key]);
                continue;
            } else if (!($BoardInfo = $this->_is_valid_board($Value['board']))) {
                return false;
            } elseif (($Value['length'] > $BoardInfo['length'] || $Value['width'] > $BoardInfo['length']) || ($Value['length'] > $BoardInfo['width'] && $Value['width'] > $BoardInfo['width'])) {
            // } elseif (($Value['length'] > MAX_LENGTH || $Value['width'] > MAX_LENGTH) || ($Value['length'] > MAX_WIDTH && $Value['width'] > MAX_WIDTH)) {
                $GLOBALS['error'] = $Value['qrcode'] . $Value['plate_name'] .  '的板块尺寸太长';
                return false;
            } else {
                $Value['purchase'] = $BoardInfo['purchase'];
                $Value['unit_price'] = $BoardInfo['saler_unit_price'];
                if ($Value['area'] < MIN_AREA) {
                    $Value['area'] = MIN_AREA;
                }
                $Value['sum'] = ceil($Value['area'] * $Value['unit_price']); // 计算价格

                $Value['thick'] = $BoardInfo['thick'];
                $Value['amount'] = intval($Value['amount']);
                if ($Value['amount'] > 1) {
                    for ($I = 1; $I < $Value['amount']; $I++) {
                        if ($Value['qrcode'] != '') {
                            array_push($Qrcode, $Value);
                        } else {
                            array_push($NoQrcode, $Value);
                        }
                    }
                }
            }
            $BoardPlate[$Key] = $Value;
            if (preg_match(REG_ORDER_QRCODE, $Value['qrcode'], $Matches)) {
                $Matches[3] = intval($Matches[3]);
                if ($Matches[3] > $MaxNum) {
                    $MaxNum = $Matches[3];
                }
                if ($Prefix == '') {
                    $Prefix = $Matches[1] . '-' . $Matches[2] . '-';
                }
            }
        }
        if (!empty($Qrcode)) {
            foreach ($Qrcode as $Key => $Value) {
                $MaxNum++;
                $Value['qrcode'] = $Prefix . $MaxNum;
                if ($Value['bd_file'] != '') {
                    $Source = $Value['bd_file'];
                    $Value['bd_file'] = preg_replace(REG_ORDER_QRCODE, $Value['qrcode'], $Value['bd_file']);
                    copy($Source, $Value['bd_file']);
                }
                array_push($BoardPlate, $Value);
            }
        }
        if (!empty($NoQrcode)) {
            $BoardPlate = array_merge($BoardPlate, $NoQrcode);
        }
        if (count($BoardPlate) > 0) {
            self::$_BoardPlate = $BoardPlate;
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
    private function _add_order_product_board_plate(){
        $BoardPlate = self::$_BoardPlate;
        $OrderProductId = $this->_OderProductId;
        $this->_CI->load->helper('dismantle_helper');
        $Opbids = array(); /*已经存在的订单产品板材统计Id号*/
        $Board = array(); /*板块中包含的板材*/
        foreach ($BoardPlate as $key => $value){
            if(!isset($Board[$value['board']])){
                $Board[$value['board']] = array(
                    'order_product_id' => $OrderProductId,
                    'board' => $value['board'],
                    'purchase' => $value['purchase'],
                    'unit_price' => $value['unit_price'],
                    'sum' => $value['sum'],
                    'virtual_sum' => $value['sum'],
                    'amount' => ONE,
                    'area' => $value['area'],
                    'virtual_area' => $value['area']
                );
                if(!($Board[$value['board']]['v'] = $this->_CI->order_product_board_model->is_existed($OrderProductId, gh_escape($value['board'])))){
                    /*如果不存在则插入订单产品板材*/
                    log_message('debug', $value['board']);
                    $Board[$value['board']] = gh_escape($Board[$value['board']]);
                    $Board[$value['board']]['v'] = $this->_CI->order_product_board_model->insert($Board[$value['board']]);
                }
                array_push($Opbids, $Board[$value['board']]['v']);
            }else{
                $Board[$value['board']]['amount']++;
                $Board[$value['board']]['area'] = bcadd($Board[$value['board']]['area'], $value['area']);
                // $Board[$value['board']]['area'] += $value['area'];
                $Board[$value['board']]['virtual_area'] = bcadd($Board[$value['board']]['virtual_area'], $value['area']);
                // $Board[$value['board']]['virtual_area'] += $value['area'];
                $Board[$value['board']]['sum'] += $value['sum'];
                $Board[$value['board']]['virtual_sum'] += $value['sum'];
            }
            $value['order_product_board_id'] = $Board[$value['board']]['v'];

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
        foreach ($Board as $Key => $Value) {
            $Board[$Key]['sum'] = ceil($Value['area'] * $Value['unit_price']);
            $Board[$Key]['virtual_sum'] = ceil($Value['virtual_area'] * $Value['unit_price']);
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
            $GLOBALS['error'] .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'保存拆单板块失败!';
            return false;
        }
    }

    /**
     * 编辑柜体结构
     */
    private function _edit_order_product_wardrobe_struct () {
        if (!empty($this->_WardrobeStructId)) {
            $this->_CI->order_product_wardrobe_struct_model->update(self::$_WardrobeStruct, $this->_WardrobeStructId);
        } else {
            $this->_CI->order_product_wardrobe_struct_model->insert(self::$_WardrobeStruct);
        }
    }

    /**
     * 复制订单
     */
    public function repeat ($To, $From) {
        $this->_Save = 'dismantling';

        $this->_OderProductId = $To['v'];
        $this->_OrderProductNum = $To['order_product_num'];
        $this->_OrderProduct['bd'] = $From['bd'];
        $this->_OrderProduct['product'] = $From['product'];
        $this->_OrderProduct['remark'] = $From['order_product_remark'];
        $this->_edit_order_product();
        $this->_get_wardrobe_struct($From['order_product_id']);

        $this->_get_board_plate($From['order_product_id']);

        if (!empty(self::$_WardrobeStruct)) {
            $this->_edit_order_product_wardrobe_struct();
        }
        if (!empty(self::$_BoardPlate)) {
            $this->_add_order_product_board_plate();
        }
        return $this->_workflow();
    }

    /**
     * 获取柜体结构
     * @param $OrderProductId
     */
    private function _get_wardrobe_struct ($OrderProductId) {
        if (empty(self::$_WardrobeStruct)) {
            if (!!($WardrobeStruct = $this->_CI->order_product_wardrobe_struct_model->select_one(array('order_product_id' => $OrderProductId)))) {
                unset($WardrobeStruct['v']);
                $WardrobeStruct['order_product_id'] = $this->_OderProductId;
                self::$_WardrobeStruct = $WardrobeStruct;
            } else {
                $GLOBALS['error'] = '';
                return false;
            }
        }
        return true;
    }

    /**
     * 获取板块清单
     * @param $OrderProductId
     */
    private function _get_board_plate ($OrderProductId) {
        if (empty(self::$_BoardPlate)) {
            if (!!($Query = $this->_CI->order_product_board_plate_model->select_by_order_product_id(array('order_product_id' => $OrderProductId)))) {
                $BoardPlate = $Query['content'];
                unset($Query);
                foreach ($BoardPlate as $Key => $Value) {
                    unset($Value['v'], $Value['scanner'], $Value['scan_datetime']);
                    if ($Value['qrcode'] != '') {
                        $Value['qrcode'] = preg_replace(REG_ORDER_PRODUCT, $this->_OrderProductNum, $Value['qrcode']);
                    }
                    if ($Value['bd_file'] != '') {
                        $Source = $Value['bd_file'];
                        $Value['bd_file'] = preg_replace(REG_ORDER_PRODUCT, $this->_OrderProductNum, $Value['bd_file']);
                        copy($Source, $Value['bd_file']);
                    }
                    $Value['sum'] = ceil(($Value['area'] * $Value['unit_price']) * M_REGULAR) / M_REGULAR;
                    $BoardPlate[$Key] = $Value;
                }
                self::$_BoardPlate = $BoardPlate;
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
        return $this->_CI->order_product_board_model->delete_by_order_product_id($Id);
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
