<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 20:23
 */
class Arrange_work {
    private $_CI;
    private $_Usergroup = array(
        '电子锯', '封边', '打孔', '扫描', '打包'
    );
    private $_Data;
    private $_Failue;
    public function __construct() {
        $this->_CI = &get_instance();
    }
    public function stop ($Data) {
        $this->_Data = $Data;
        if ($this->_Data['status'] == START_WORK) {
            switch ($this->_Data['usergroup']) {
                case '电子锯':
                    $this->_arrange_electronic_saw();
                    break;
                case '封边':
                    $this->_arrange_edge();
                    break;
                case '打孔':
                    $this->_arrange_punch();
                    break;
                case '扫描':
                    $this->_arrange_scan();
                    break;
                case '打包':
                    $this->_arrange_pack();
                    break;
            }
        } elseif ($this->_Data['status'] == OFFTIME) {

        } else {

        }
        return true;
    }
    public function offtime ($Data) {
        $this->_Data = $Data;
        if ($this->_Data['status'] == START_WORK) {
            switch ($this->_Data['usergroup']) {
                case '电子锯':
                    $this->_arrange_electronic_saw();
                    break;
                case '封边':
                    $this->_arrange_edge();
                    break;
                case '打孔':
                    $this->_arrange_punch();
                    break;
                case '扫描':
                    $this->_arrange_scan();
                    break;
                case '打包':
                    $this->_arrange_pack();
                    break;
            }
        } elseif ($this->_Data['status'] == STOP_WORK) {

        } else {

        }
        return true;
    }

    private function _arrange_electronic_saw() {
        $this->_CI->load->model('order/mrp_model');
        if (!!($Mrp = $this->_CI->mrp_model->select_user_current_work($this->_Data['v'], M_ELECTRONIC_SAW))) {
            foreach ($Mrp as $Key => $Value) {
                $Mrp[$Key] = $Value['v'];
            }
            $this->_CI->load->library('workflow/workflow_mrp/workflow_mrp');
            if (!!($this->_CI->workflow_mrp->initialize($Mrp))) {
                $this->_CI->workflow_mrp->re_shear();
            } else {
                $this->_Failue = $this->_CI->workflow_mrp->get_failue();
            }
        }
        return true;
    }
    private function _arrange_edge () {
        $this->_CI->load->model('order/order_product_board_model');
        if (!!($OrderProductBoard = $this->_CI->order_product_board_model->select_user_current_work($this->_Data['v'], OPB_EDGING))) {
            foreach ($OrderProductBoard as $Key => $Value) {
                $OrderProductBoard[$Key] = $Value['v'];
            }
            $this->_CI->load->library('workflow/workflow_order_product_board/workflow_order_product_board');
            if (!!($this->_CI->workflow_order_product_board->initialize($OrderProductBoard))) {
                $this->_CI->workflow_order_product_board->re_edge();
            } else {
                $this->_Failue = $this->_CI->workflow_mrp->get_failue();
            }
        }
        return true;
    }
    private function _arrange_punch () {
        $this->_CI->load->model('order/order_product_board_model');
        if (!!($OrderProductBoard = $this->_CI->order_product_board_model->select_user_current_work($this->_Data['v']))) {
            foreach ($OrderProductBoard as $Key => $Value) {
                $OrderProductBoard[$Key] = $Value['v'];
            }
            $this->_CI->load->library('workflow/workflow_order_product_board/workflow_order_product_board');
            if (!!($this->_CI->workflow_order_product_board->initialize($OrderProductBoard))) {
                $this->_CI->workflow_order_product_board->re_punch();
            } else {
                $this->_Failue = $this->_CI->workflow_mrp->get_failue();
            }
        }
        return true;
    }
    private function _arrange_scan () {
        $this->_CI->load->model('order/order_product_board_model');
        if (!!($OrderProductBoard = $this->_CI->order_product_board_model->select_user_current_work($this->_Data['v'], OPB_SCANNING))) {
            foreach ($OrderProductBoard as $Key => $Value) {
                $OrderProductBoard[$Key] = $Value['v'];
            }
            $this->_CI->load->library('workflow/workflow_order_product_board/workflow_order_product_board');
            if (!!($this->_CI->workflow_order_product_board->initialize($OrderProductBoard))) {
                $this->_CI->workflow_order_product_board->re_sscan();
            } else {
                $this->_Failue = $this->_CI->workflow_mrp->get_failue();
            }
        }
        return true;
    }
    private function _arrange_pack () {
        $this->_CI->load->model('order/order_product_board_model');
        if (!!($OrderProductBoard = $this->_CI->order_product_board_model->select_user_current_work($this->_Data['v'], OPB_PACKING))) {
            foreach ($OrderProductBoard as $Key => $Value) {
                $OrderProductBoard[$Key] = $Value['v'];
            }
            $this->_CI->load->library('workflow/workflow_order_product_board/workflow_order_product_board');
            if (!!($this->_CI->workflow_order_product_board->initialize($OrderProductBoard))) {
                $this->_CI->workflow_order_product_board->re_ppack();
            } else {
                $this->_Failue = $this->_CI->workflow_mrp->get_failue();
            }
        }
        return true;
    }
}