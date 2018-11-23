<?php namespace Dismantle;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月13日
 * @author Administrator
 * @version
 * @des
 */
abstract class  D_abstract {
    protected $_CI;
    protected $_OderProductId;
    protected $_OrderProduct = array();

    static $_Edge;
    static $_Board;
    static $_Abnormity;

    public function __construct(){
        $this->_CI = &get_instance();
    }

    protected function _get_edge () {
        $this->_CI->load->model('data/edge_model');
        if (!!($Edge = $this->_CI->edge_model->select())) {
            foreach ($Edge as $Key => $Value) {
                self::$_Edge[$Value['name'] . $Value['thick']] = $Value;
            }
            return true;
        }
        return false;
    }
    protected function _get_edge_thick($Plate){
        if (!isset(self::$_Edge)) {
            $this->_get_edge();
        }

        if (isset(self::$_Edge)) {
            $Key = $Plate['edge'] . $Plate['thick'];
            if (isset(self::$_Edge[$Key])) {
                $Edge = self::$_Edge[$Key];
            } else {
                $Key = $Plate['edge'] . ZERO;
                if (isset(self::$_Edge[$Key])) {
                    $Edge = self::$_Edge[$Key];
                }
            }
        }
        if (isset($Edge)) {
            $Return['up_edge'] = !empty($Edge['ups']) ? $Edge['ups'] : O_EDGE;
            $Return['down_edge'] = !empty($Edge['downs']) ? $Edge['downs'] : O_EDGE;
            $Return['left_edge'] = !empty($Edge['lefts']) ? $Edge['lefts'] : O_EDGE;
            $Return['right_edge'] = !empty($Edge['rights']) ? $Edge['rights'] : O_EDGE;
        } else {
            $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = O_EDGE;
        }
        return $Return;
    }

    private function _get_board () {
        $this->_CI->load->model('product/board_model');
        if (!!($Board = $this->_CI->board_model->select())) {
            foreach ($Board as $Key => $Value) {
                if (empty($Value['length'])) {
                    $Value['length'] = MAX_LENGTH;
                }
                if (empty($Value['width'])) {
                    $Value['width'] = MAX_WIDTH;
                }
                self::$_Board[$Value['name']] = $Value;
            }
            return true;
        }
        return false;
    }
    protected function _is_valid_board ($BoardPlate) {
        if (!isset(self::$_Board)) {
            $this->_get_board();
        }
        if (isset(self::$_Board)) {
            if (isset(self::$_Board[$BoardPlate])) {
                return self::$_Board[$BoardPlate];
            } else {
                $GLOBALS['error'] = $BoardPlate . '不在系统中, 请先登记板材!';
            }
        } else {
            $GLOBALS['error'] = '系统中没有板块信息';
        }
        return false;
    }

    /**
     * 获取异形信息
     * @param $Name
     */
    private function _get_abnormity () {
        $this->_CI->load->model('data/abnormity_model');
        if (!!($Abnormity = $this->_CI->abnormity_model->select(array(), YES))) {
            foreach ($Abnormity as $Key => $Value) {
                self::$_Abnormity[$Value['name']] = $Value;
            }
            return true;
        }
        return false;
    }
    /**
     * 判断是否为异形
     * @param unknown $Name
     */
    protected function _is_abnormity($Name){
        if (!isset(self::$_Abnormity)) {
            $this->_get_abnormity();
        }
        $Flag = 0;
        if(!empty(self::$_Abnormity)){
            foreach (self::$_Abnormity as $key => $value){
                if(preg_match('/'.$value['name'].'/', $Name)){
                    $Flag = 1;
                    break;
                }
            }
        }
        return $Flag;
    }

    /**
     * 编辑订单产品
     * @param unknown $OrderProduct
     * @param unknown $Opid
     */
    protected function _edit_order_product(){
        if (empty($this->_OrderProduct['product'])) {
            unset($this->_OrderProduct['product']);
        }
        if (empty($this->_OrderProduct['remark'])) {
            unset($this->_OrderProduct['remark']);
        }
        if (!empty($this->_OrderProduct)) {
            $Set = gh_escape($this->_OrderProduct);
            return $this->_CI->order_product_model->update($Set, $this->_OderProductId);
        }
        return true;
    }
    
    abstract public function read();
    abstract public function edit($Save);

    /*abstract public function dismantling();
    abstract public function dismantled();*/
    
    /**
     * 清除已经拆单保存的内容
     */
    abstract public function remove($Id);
}