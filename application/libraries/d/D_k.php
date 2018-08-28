<?php namespace Dismantle;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Zhangcc
 * @version
 * @des
 */

require_once dirname(__FILE__).'/D_abstract.php';

class D_k extends D_abstract{
    private $_Save;
    private $_W;

    private $_OrderProductNum;
    static $_BoardPlate;

    public function __construct(){
        parent::__construct();
        $this->_CI->load->model('order/order_product_model');
        $this->_CI->load->model('order/order_product_board_model');
        $this->_CI->load->model('order/order_product_board_wood_model');
        $this->_CI->load->library('workflow/workflow');
        $this->_W = $this->_CI->workflow->initialize('order_product');
    }

    public function edit ($Save) {
        $this->_Save = $Save;
        $this->_OderProductId = $this->_CI->input->post('v', true);
        $this->_OderProductId = intval(trim($this->_OderProductId));
        $this->_OrderProduct['product'] = $this->_CI->input->post('product', true);
        $this->_OrderProduct['remark'] = $this->_CI->input->post('remark', true);

        self::$_BoardPlate = $this->_CI->input->post('order_product_board_plate', true);

        if (empty($this->_OderProductId)) {
            $GLOBALS['error'] = '请选择需要确认的订单产品!';
            return false;
        } elseif (empty(self::$_BoardPlate) || !($this->_check_board_plate())) {
            $GLOBALS['error'] = '请添加木框门信息!';
            return false;
        } else {
            $this->_edit_order_product();
            $this->_add_order_product_board_wood();
            return $this->_workflow();
        }
    }

    /**
     * 复制板块信息
     */
    private function _check_board_plate () {
        $BoardPlate = self::$_BoardPlate;
        $NoQrcode = array();
        foreach ($BoardPlate as $Key => $Value) {
            $Value['board'] = trim($Value['board']);
            if ($Value['board'] == '' || $Value['wood_name'] == '' || empty($Value['length']) || empty($Value['width'])) {
                unset($BoardPlate[$Key]);
                continue;
            } else if (!($BoardInfo = $this->_is_valid_board($Value['board']))) {
                return false;
            } else {
                $Value['purchase'] = $BoardInfo['purchase'];
                $Value['unit_price'] = $BoardInfo['saler_unit_price'];
                if ($Value['area'] < MIN_K_AREA) {
                    $Value['area'] = MIN_K_AREA;
                }
                $Value['sum'] = ceil(($Value['area'] * $Value['unit_price']) * M_REGULAR) / M_REGULAR; // 计算价格

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
    private function _add_order_product_board_wood(){
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
                    'sum' => $value['sum']
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
                $Board[$value['board']]['sum'] += $value['sum'];
            }
            $value['order_product_board_id'] = $Board[$value['board']]['v'];

            $BoardPlate[$key] = $value;
        }
        $this->_CI->order_product_board_wood_model->delete_by_order_product_id($OrderProductId);
        if(!empty($Opbids)){
            $this->_CI->order_product_board_model->delete_not_in($OrderProductId, $Opbids);
        }
        $BoardPlate = gh_escape($BoardPlate);
        if(!!($this->_CI->order_product_board_wood_model->insert_batch($BoardPlate))
            && !!($this->_CI->order_product_board_model->update_batch($Board))){
            return true;
        }else{
            $GLOBALS['error'] .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'保存拆单板块失败!';
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

        $this->_get_board_plate($From);

        if (!empty(self::$_BoardPlate)) {
            $this->_add_order_product_board_wood();
        }
        return $this->_workflow();
    }

    /**
     * 获取板块清单
     * @param $OrderProductId
     */
    private function _get_board_plate ($OrderProductId) {
        if (empty(self::$_BoardPlate)) {
            if (!!($Query = $this->_CI->order_product_board_wood_model->select_by_order_product_id(array('order_product_id' => $OrderProductId)))) {
                $BoardPlate = $Query['content'];
                unset($Query);
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