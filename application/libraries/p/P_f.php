<?php namespace Post_sale;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Zhangcc
 * @version
 * @des 服务类拆单
 */
require_once dirname(__FILE__).'/P_abstract.php';

class P_f extends P_abstract{
    private $_Save;
    private $_W;

    static $_Server;
    static $_Servers;

    public function __construct(){
        parent::__construct();
        $this->_CI->load->model('order/order_product_model');
        $this->_CI->load->model('order/order_product_server_model');
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

        self::$_Server = $this->_CI->input->post('order_product_board_plate', true);

        if (empty($this->_OderProductId)) {
            $GLOBALS['error'] = '请选择需要确认的订单产品!';
            return false;
        } elseif (empty(self::$_Server) || !($this->_check_server())) {
            $GLOBALS['error'] = '请添加服务信息!';
            return false;
        } else {
            $this->_edit_order_product();
            $this->_add_order_product_server();
            return $this->_workflow();
        }
    }

    /**
     * 复制板块信息
     */
    private function _check_server () {
        $Server = self::$_Server;
        $MergeServer = array();
        foreach ($Server as $Key => $Value) {
            if (empty($Value['server']) || empty($Value['amount'])) {
                unset($Server[$Key]);
                continue;
            } else {
                if (!($ServerInfo = $this->_is_valid_server($Value['goods_speci_id'], $Value['server']))) {
                    $ServerInfo = array(
                        'goods_speci_id' => 0,
                        'purchase_unit' => '--',
                        'purchase' => 0,
                        'unit_price' => 0
                    );
                }
                if (empty($Value['goods_speci_id'])) {
                    $Value['goods_speci_id'] = $ServerInfo['goods_speci_id'];
                }
                if (empty($Value['purchase_unit'])) {
                    $Value['purchase_unit'] = $ServerInfo['purchase_unit'];
                }
                if (empty($Value['purchase'])) {
                    $Value['purchase'] = $ServerInfo['purchase'];
                }
                if (empty($Value['unit_price']) && $Value['unit_price'] != 0) {
                    $Value['unit_price'] = $ServerInfo['unit_price'];
                }
                $Value['amount'] = floatval($Value['amount']);
                $Value['sum'] = ceil($Value['amount'] * $Value['unit_price']); // 计算价格
                $this->_OrderProduct['sum'] += $Value['sum'];
                $Value['order_product_id'] = $this->_OderProductId;
            }
            $MergeServer[$Key] = $Value;
        }

        if (count($MergeServer) > 0) {
            self::$_Server = array_values($MergeServer);
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
    private function _add_order_product_server(){
        $Server = self::$_Server;
        $this->_CI->order_product_server_model->delete_by_order_product_id($this->_OderProductId);
        $Server = gh_escape($Server);
        if(!!($this->_CI->order_product_server_model->insert_batch($Server))){
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

    private function _is_valid_server ($GoodsSpeci, $Server) {
        if (!isset(self::$_Servers)) {
            $this->_get_servers();
        }
        if (isset(self::$_Servers)) {
            if (isset(self::$_Servers[$GoodsSpeci])) {
                return self::$_Servers[$GoodsSpeci];
            } else {
                // $GLOBALS['error'] = $Server . '不在系统中, 请先登记服务!';
            }
        } else {
            // $GLOBALS['error'] = '系统中没有服务信息';
        }
        return false;
    }
    private function _get_servers () {
        $this->_CI->load->model('product/goods_speci_model');
        if (!!($Servers = $this->_CI->goods_speci_model->select_by_product_code('F'))) {
            foreach ($Servers['content'] as $Key => $Value) {
                self::$_Servers[$Value['v']] = $Value;
            }
            return true;
        }
        return false;
    }
}