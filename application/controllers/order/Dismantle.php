<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月16日
 * @author Zhangcc
 * @version
 * @des
 * 拆单
 */
class Dismantle extends MY_Controller{
    private $__Search = array(
        'order_id' => ZERO,
        'order_product_id' => ZERO
    );
    private $_Id; // 订单产品编号
    private $_Type;
    
    private $_Save; 
    /**
     * 选中的订单产品信息
     * @var ArrayAccess
     */
    private $_Select = array('opid' => 0, 'product' => '', 'code' => '', 'remark' => '');
    private $_Code;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Dismantle __construct Start!');
        $this->load->model('order/order_model');
        $this->load->model('order/order_product_model');
        $this->load->model('product/product_model');
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['order_product_id'])) {
            $OrderProductId = $this->input->get('v', true);
            $OrderProductId = intval($OrderProductId);
            if (!empty($OrderProductId)) {
                $this->_Search['order_product_id'] = $OrderProductId;
            }
        }
        $Data = array();
        if (empty($this->_Search['order_id'])) {
            if (empty($this->_Search['order_product_id'])) {
                $this->Code = EXIT_ERROR;
                $this->Message = '请选择需要拆的订单';
            } else {
                if (!($Data['order_info'] = $this->order_product_model->is_order_dismantlable($this->_Search['order_product_id']))) {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '订单当前状态不可拆!';
                }
            }
        } else {
            if (!($Data['order_info'] = $this->order_model->is_dismantlable($this->_Search['order_id']))) {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单当前状态不可拆!';
            }
        }
        if ($this->Code == EXIT_SUCCESS) {
            $OrderProducts = array();
            if (!!($Query = $this->order_product_model->select_dismantle($Data['order_info']['order_id']))) {
                foreach ($Query as $Key => $Value) {
                    if (!isset($OrderProducts[$Value['product_id']])) {
                        $OrderProducts[$Value['product_id']] = array();
                    }
                    array_push($OrderProducts[$Value['product_id']], $Value);
                    if (!isset($Data['order_info']['code']) && in_array($Value['status'], array(OP_CREATE, OP_DISMANTLING))) {
                        $Data['order_info']['code'] = $Value['code'];
                        $Data['order_info']['order_product_id'] = $Value['v'];
                    }
                }
                if (!isset($Data['order_info']['code'])) {
                    $Data['order_info']['code'] = CABINET_NUM; // 如果没有可以拆单的产品就默认显示橱柜
                }
            }
            if (!!($Product = $this->product_model->select(array('undelete' => YES, 'p' => ONE, 'pn' => ONE, 'pagesize' => ALL_PAGESIZE)))) {
                foreach ($Product['content'] as $Key => $Value) {
                    $Value['remark'] = ''; // 备注清空
                    $Value['set'] = ONE; // 默认设置一套
                    $Data[$Value['code']] = array();
                    $Data[$Value['code']]['product'] = $Value;
                    if (isset($OrderProducts[$Value['v']])) {
                        $Data[$Value['code']]['order_product'] = $OrderProducts[$Value['v']];
                    }
                }
            }
        }
        $this->_ajax_return($Data);
    }

    /**
     * 拆单
     * @param number $this->_Save 拆单提交类型  
     */
    public function edit(){
        $Save = $this->input->post('save', true);
        if (empty($Save)) {
            $Save = 'dismantling';
        }
        if (!!($OrderProduct = $this->_is_dismantlable())) {
            $this->load->library('d/d');
            if (!!($D = $this->d->initialize($OrderProduct['code']))) {
                if (!($D->edit($Save))) {
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品拆单出错!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您拆的订单产品类型不存在!';
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品不可拆单!';
        }
        $this->_ajax_return();
    }
    
    /**
     * 清除当前的拆单数据
     */
    public function remove(){
        $OrderProductId = $this->input->post('order_product_id', true);
        $OrderProductId = intval($OrderProductId);
        if (empty($OrderProductId)) {
            $OrderProductId = $this->input->post('v', true);
            $OrderProductId = intval($OrderProductId);
        }
        if (empty($OrderProductId)) {
            $this->Code = EXIT_ERROR;
            $this->Message = '请选择需要清除的订单!';
        } else {
            if (!!($OrderProduct = $this->order_product_model->is_dismantlable($OrderProductId))) {
                $this->load->library('d/d');
                if (!!($D = $this->d->initialize($OrderProduct['code']))) {
                    if (!($D->remove($OrderProductId))) {
                        $this->Code = EXIT_ERROR;
                        $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单板块清除失败!';
                    }
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单已经确认拆单或者删除，不能清除板块数据!';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 暂时保存拆单
     */
    public function dismantling () {
        if (!!($OrderProduct = $this->_is_dismantlable())) {
            $this->load->library('d/d');
            if (!!($D = $this->d->initialize($OrderProduct['code']))) {
                if (!($D->edit('dismantling'))) {
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单板块拆单出错!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '订单产品拆单出错!';
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单板块拆单出错!';
        }
        $this->_ajax_return();
    }

    public function dismantled () {
        if (!!($OrderProduct = $this->_is_dismantlable())) {
            if ($this->_check_bd($OrderProduct)) {
                $this->load->library('d/d');
                if (is_array($this->_Id)) {
                    foreach ($OrderProduct as $Key => $Value) {
                        if (!!($D = $this->d->initialize($Value['code']))) {
                            if (!($D->dismantled($Value['order_product_id']))) {
                                $this->Code = EXIT_ERROR;
                                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error'] : $Value['num'] . '订单产品确认拆单出错!';
                                break;
                            }
                        } else {
                            $this->Code = EXIT_ERROR;
                            $this->Message = $Value['num'] . '订单产品确认拆单出错!';
                            break;
                        }
                    }
                } else {
                    if (!!($D = $this->d->initialize($OrderProduct['code']))) {
                        if (!($D->dismantled())) {
                            $this->Code = EXIT_ERROR;
                            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品确认拆单出错!';
                        }
                    } else {
                        $this->Code = EXIT_ERROR;
                        $this->Message = '您拆的订单类型不存在!';
                    }
                }
            } else {
                $this->Code = EXIT_ERROR;
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'订单产品确认拆单出错!';
        }
        $this->_ajax_return();
    }

    private function _is_dismantlable () {
        $this->_Id = $this->input->post('order_product_id', true);
        $this->_Id = intval($this->_Id);
        if (empty($this->_Id)) {
            $this->_Id = $this->input->post('v', true);
            if (is_array($this->_Id)) {
                $this->_Id = array_map('intval', $this->_Id);
            } else {
                $this->_Id = intval($this->_Id);
            }
        }
        if (empty($this->_Id)) {
            $GLOBALS['error'] = '请选择需要拆的订单!';
        } else {
            $ReDismantle = $this->input->post('re_dismantle', true);
            if ($ReDismantle) {
                $this->_re_dismantle();
            }
            if (is_array($this->_Id) && !!($OrderProduct = $this->order_product_model->are_dismantlable($this->_Id))) {
                return $OrderProduct;
            } elseif (!!($OrderProduct = $this->order_product_model->is_dismantlable($this->_Id))) {
                return $OrderProduct;
            } else {
                $GLOBALS['error'] = '订单已经确认拆单或者删除，不能再次确认!';
            }
        }
        return false;
    }

    /**
     * 重新拆单
     * @return bool
     */
    private function _re_dismantle () {
        $this->load->library('workflow/workflow');
        $W = $this->workflow->initialize('order_product');
        if ($W->initialize($this->_Id)) {
            $W->re_dismantle();
            return true;
        } else {
            $GLOBALS['error'] = $this->_W->get_failue();
        }
        return false;
    }

    private function _check_bd ($OrderProduct) {
        $V = array();
        if (is_array($this->_Id)) {
            foreach ($OrderProduct as $Key => $Value) {
                if ($Value['code'] == CABINET_NUM || $Value['code'] == WARDROBE_NUM) {
                    array_push($V, $Value['order_product_id']);
                }
            }
        } else {
            if ($OrderProduct['code'] == CABINET_NUM || $OrderProduct['code'] == WARDROBE_NUM) {
                array_push($V, $OrderProduct['order_product_id']);
            }
        }
        $this->load->model('order/order_product_board_plate_model');
        if (!empty($V) && !!($Query = $this->order_product_board_plate_model->select_checked_bd($V))) {
            foreach ($Query as $Key => $Value) {
                $Query[$Key] = $Value['qrcode'];
            }
            $this->Message = implode(',', $Query) . '没有上传BD文件';
            return false;
        }
        return true;
    }

    public function disabled () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            $this->load->library('workflow/workflow');
            $W = $this->workflow->initialize('order_product');
            if ($W->initialize($Where)) {
                $W->remove();
                $this->Message = '作废成功!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = $this->_W->get_failue();
            }
        }
        $this->_ajax_return();
    }
}
