<?php namespace Dismantle;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Zhangcc
 * @version
 * @des
 */

require_once dirname(__FILE__).'/D_abstract.php';

class D_m extends D_abstract{
    private $_Save;
    private $_W;

    private $_OrderProductNum;
    static $_Door;
    private $_DoorId;
    static $_BoardPlate;

    public function __construct(){
        parent::__construct();
        $this->_CI->load->model('order/order_product_model');
        $this->_CI->load->model('order/order_product_door_model');
        $this->_CI->load->model('order/order_product_board_model');
        $this->_CI->load->model('order/order_product_board_door_model');
        $this->_CI->load->library('workflow/workflow');
        $this->_W = $this->_CI->workflow->initialize('order_product');
    }

    public function edit ($Save) {
        $this->_Save = $Save;
        $this->_OderProductId = $this->_CI->input->post('v', true);
        $this->_OderProductId = intval(trim($this->_OderProductId));
        $this->_OrderProduct['product'] = $this->_CI->input->post('product', true);
        $this->_OrderProduct['remark'] = $this->_CI->input->post('remark', true);

        self::$_Door = $this->_CI->input->post('struct', true);
        self::$_Door = gh_escape(self::$_Door);
        self::$_Door['order_product_id'] = $this->_OderProductId;
        $this->_DoorId = intval(trim(self::$_Door['v']));
        unset(self::$_Door['v']);

        self::$_BoardPlate = $this->_CI->input->post('order_product_board_plate', true);

        if (empty($this->_OderProductId)) {
            $GLOBALS['error'] = '请选择需要确认的订单产品!';
            return false;
        } elseif (empty(self::$_BoardPlate) || !($this->_check_board_plate())) {
            $GLOBALS['error'] = '请添加板块信息!';
            return false;
        } elseif (empty(self::$_Door) || !($this->_check_door())) {
            $GLOBALS['error'] = '请添加封边信息!';
            return false;
        } else {
            $this->_edit_order_product();
            if (!empty(self::$_Door)) {
                $this->_edit_order_product_door();
            }
            $this->_add_order_product_board_door();
            return $this->_workflow();
        }
    }

    private function _check_door () {
        $Door = self::$_Door;
        if ($Door['edge'] == '') {
            return false;
        }
        return true;
    }
    /**
     * 复制板块信息
     */
    private function _check_board_plate () {
        $BoardPlate = self::$_BoardPlate;
        $NoQrcode = array();
        foreach ($BoardPlate as $Key => $Value) {
            $Value['board'] = trim($Value['board']);
            if ($Value['board'] == '' || empty($Value['length']) || empty($Value['width'])) {
                unset($BoardPlate[$Key]);
                continue;
            /*} else if (!($BoardInfo = $this->_is_valid_board($Value['board']))) {
                return false;*/
            } elseif (($Value['length'] > MAX_LENGTH || $Value['width'] > MAX_LENGTH) || ($Value['length'] > MAX_WIDTH && $Value['width'] > MAX_WIDTH)) {
                $GLOBALS['error'] = $Value['board'] .  '的板块尺寸太长';
                return false;
            } else {
                /*$Value['purchase'] = $BoardInfo['purchase'];
                $Value['unit_price'] = $BoardInfo['saler_unit_price'];*/
                $Value['purchase'] = 0;
                $Value['unit_price'] = 0;
                if ($Value['area'] < MIN_M_AREA) {
                    $Value['area'] = MIN_M_AREA;
                }
                $Value['sum'] = ceil($Value['area'] * $Value['unit_price']); // 计算价格

                $Amount = intval($Value['amount']);
                $Value['amount'] = ONE;
                if ($Amount > ONE) {
                    for ($I = ONE; $I < $Amount; $I++) {
                        array_push($NoQrcode, $Value);
                    }
                }
            }
            $BoardPlate[$Key] = $Value;
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
    private function _add_order_product_board_door(){
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
                    'amount' => 1,
                    'area' => $value['area'],
                    'virtual_area' => $value['area'],
                    'open_hole' => $value['open_hole'],
                    'invisibility' => $value['invisibility'],
                    'sum' => $value['sum'],
                    'virtual_sum' => $value['sum']
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
                $Board[$value['board']]['area'] += $value['area'];
                $Board[$value['board']]['virtual_area'] += $value['area'];
                $Board[$value['board']]['open_hole'] += $value['open_hole'];
                $Board[$value['board']]['invisibility'] += $value['invisibility'];
                $Board[$value['board']]['sum'] += $value['sum'];
                $Board[$value['board']]['virtual_sum'] += $value['sum'];
            }
            $value['order_product_board_id'] = $Board[$value['board']]['v'];

            $value = array_merge($value, $this->_get_door_edge_thick(self::$_Door['edge'], $value['handle'], $value['invisibility'])); // 封边信息

            $BoardPlate[$key] = $value;
        }
        foreach ($Board as $Key => $Value) {
            $Board[$Key]['sum'] = ceil($Value['area'] * $Value['unit_price']);
            $Board[$Key]['virtual_sum'] = ceil($Value['virtual_area'] * $Value['unit_price']);
        }
        $this->_CI->order_product_board_door_model->delete_by_order_product_id($OrderProductId);
        if(!empty($Opbids)){
            $this->_CI->order_product_board_model->delete_not_in($OrderProductId, $Opbids);
        }
        $BoardPlate = gh_escape($BoardPlate);
        if(!!($this->_CI->order_product_board_door_model->insert_batch($BoardPlate))
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
    private function _edit_order_product_door () {
        if (!empty($this->_DoorId)) {
            $this->_CI->order_product_door_model->update(self::$_Door, $this->_DoorId);
        } else {
            $this->_CI->order_product_door_model->insert(self::$_Door);
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
        $this->_get_door($From);

        $this->_get_board_plate($From);

        if (!empty(self::$_Door)) {
            $this->_edit_order_product_door();
        }
        if (!empty(self::$_BoardPlate)) {
            $this->_add_order_product_board_door();
        }
        return $this->_workflow();
    }

    /**
     * 获取柜体结构
     * @param $OrderProductId
     */
    private function _get_door ($OrderProductId) {
        if (empty(self::$_Door)) {
            if (!!($Door = $this->_CI->order_product_door_model->select_one(array('order_product_id' => $OrderProductId)))) {
                unset($Door['v']);
                $Door['order_product_id'] = $this->_OderProductId;
                self::$_Door = $Door;
            } else {
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
            if (!!($Query = $this->_CI->order_product_board_door_model->select_by_order_product_id(array('order_product_id' => $OrderProductId)))) {
                $BoardPlate = $Query['content'];
                unset($Query);
                foreach ($BoardPlate as $Key => $Value) {
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

    private function _get_door_edge_thick($Value, $Handle, $Invisibility){
        if(preg_match('/双色/',$Value) || preg_match('/同色/', $Value)){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
            if('定尺拉手' == $Handle){
                $Return['left_edge'] = $Return['right_edge'] = 1.5;
            } else {
                if($Invisibility > 0){
                    $Return['left_edge'] = $Return['right_edge'] = 18.5;
                }
            }
        }elseif (preg_match('/哑光窄边/',$Value) || preg_match('/碰角/',$Value)){
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 2;
            if($Invisibility > 0){
                $Return['left_edge'] = $Return['right_edge'] = 19;
            }
        }else{
            $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 0;
        }
        return $Return;
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