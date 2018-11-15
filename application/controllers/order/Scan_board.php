<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author zhangcc
 * @version
 * @des
 * 板件扫描
 */
class Scan_board extends MY_Controller{
    private $__Search = array(
        'qrcode' => '',
        'thick' => ''
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Scan_board Start!');
        $this->load->model('order/order_product_board_plate_model');
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        /*$Qrcode = $this->input->get('qrcode', TRUE);
        $Qrcode = trim($Qrcode);*/
        $Qrcode = $this->_Search['qrcode'];
        $Data = array();
        if($Qrcode && preg_match(REG_ORDER_QRCODE, $Qrcode)){
            log_message('debug', '$Qrcode is'.$Qrcode);
            $this->load->model('order/order_product_board_plate_model');
            if(!!($OrderProduct = $this->order_product_board_plate_model->is_exist($Qrcode))){
                if ($OrderProduct['status'] < O_PRE_PRODUCE) {
                    $this->Message .= '该订单还未进入生产...';
                    $this->Code = EXIT_ERROR;
                } elseif ($OrderProduct['scan_status'] < WP_SCAN) {
                    $this->Message .= '该订单还未进入扫描环节...正在' . $this->_read_workflow_procedure($OrderProduct['scan_status']);
                    $this->Code = EXIT_ERROR;
                } else {
                    if ($OrderProduct['thick'] > THICK) {
                        if ($this->_Search['thick'] === '0') {
                            $this->_Search['thick'] = '';
                        }
                        $Thick = true;
                    } else {
                        if ($this->_Search['thick'] === '1') {
                            $this->_Search['thick'] = '';
                        }
                        $Thick = false;
                    }
                    $Data = $this->order_product_board_plate_model->select_scan_list($OrderProduct['order_product_id'], $this->_Search['thick']);
                    $ScanDatetime = '0000-00-00 00:00:00';
                    foreach ($Data['content'] as $Key => $Value) {
                        if (!empty($Value['scanner'])) {
                            $Data['content'][$Key]['checked'] = !empty($Value['scanner']);
                            if ((($Thick && $Value['thick'] > THICK) || (!$Thick && $Value['thick'] < THICK)) && $Value['scan_datetime'] > $ScanDatetime) { // 定位最后扫描人
                                $Data['last_scanner'] = $Value['scanner'];
                                $ScanDatetime = $Value['scan_datetime'];
                            } else {
                                if (empty($Data['last_scanner'])) {
                                    $Data['last_scanner'] = $Value['scanner'];
                                }
                            }
                        }
                    }
                    $Data['order_product'] = $OrderProduct;
                    $this->Message = '获取待扫描列表成功';
                }
            } else {
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'该订单还不存在';
                $this->Code = EXIT_ERROR;
            }
        }else{
            $this->Message = '二维码格式不正确!';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    private function _read_workflow_procedure ($WorkflowProcedure) {
        $this->load->model('workflow/workflow_procedure_model');
        if (!!($Query = $this->workflow_procedure_model->is_exist($WorkflowProcedure))) {
            return $Query['label'];
        }
        return '';
    }

    public function edit(){
        $V = $this->input->post('v', true);
        if (!is_array($V)) {
            $_POST['v'] = explode(',',$V);
        }
        if($this->_do_form_validation()){
            $V = $this->input->post('v');
            $OrderProductBoardPlateId = $V[array_rand($V, ONE)];
            $this->load->model('order/order_product_board_plate_model');
            if(!!($this->order_product_board_plate_model->update_scan($V))){
                $this->_edit_workflow($OrderProductBoardPlateId);
                $this->Message .= '扫描保存成功, 刷新后生效!';
            } else {
                $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'扫描保存失败';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_return();
    }

    private function _edit_workflow ($V) {
        if(!!($OrderProduct = $this->order_product_board_plate_model->is_exist('', $V))){
            $Data = $this->order_product_board_plate_model->select_scan_list($OrderProduct['order_product_id']);
            $OrderProductClassify = array();
            $OrderProductBoard = array();
            foreach ($Data['content'] as $Key => $Value) {
                if (!empty($Value['order_product_classify_id'])) {
                    if (!isset($OrderProductClassify[$Value['order_product_classify_id']])) {
                        $OrderProductClassify[$Value['order_product_classify_id']] = array(
                            'scan' => ZERO,
                            'scan_datetime' => date('Y-m-d H:i:s')
                        );
                    }
                    if (empty($Value['scanner'])) {
                        $OrderProductClassify[$Value['order_product_classify_id']]['scan_datetime'] = null;
                    } else {
                        $OrderProductClassify[$Value['order_product_classify_id']]['scan'] = $this->session->userdata('uid');
                    }
                } else {
                    if (!isset($OrderProductBoard[$Value['order_product_board_id']])) {
                        $OrderProductBoard[$Value['order_product_board_id']] = array(
                            'scan' => ZERO,
                            'scan_datetime' => date('Y-m-d H:i:s')
                        );
                    }
                    if (empty($Value['scanner'])) {
                        $OrderProductBoard[$Value['order_product_board_id']]['scan_datetime'] = null;
                    } else {
                        $OrderProductBoard[$Value['order_product_board_id']]['scan'] = $this->session->userdata('uid');
                    }
                }
            }
            if (!empty($OrderProductClassify)) {
                $this->_edit_order_product_classify($OrderProductClassify);
            }
            if (!empty($OrderProductBoard)) {
                $this->_edit_order_product_board($OrderProductBoard);
            }
            return true;
        } else {
            return false;
        }
    }

    private function _edit_order_product_classify ($OrderProductClassify) {
        $W = $this->_workflow_order_product_classify();
        foreach ($OrderProductClassify as $Key => $Value) {
            if (empty($Value['scan'])) {
                continue;
            } else {
                if ($W->initialize($Key, $Value)) {
                    if (empty($Value['scan_datetime'])) {
                        $W->scanning();
                    } else {
                        $W->scanned();
                    }
                } else {
                    $this->Message = $W->get_failue();
                    break;
                }
            }
        }
        return  true;
    }

    private function _edit_order_product_board ($OrderProductBoard) {
        $W = $this->_workflow_order_product_board();
        foreach ($OrderProductBoard as $Key => $Value) {
            if (empty($Value['scan'])) {
                continue;
            } else {
                if ($W->initialize($Key, $Value)) {
                    if (empty($Value['scan_datetime'])) {
                        $W->scanning();
                    } else {
                        $W->scanned();
                    }
                } else {
                    $this->Message = $W->get_failue();
                    break;
                }
            }
        }
        return  true;
    }

    private function _workflow_order_product_classify () {
        $this->load->library('workflow/workflow');
        $this->load->model('order/order_product_classify_model');
        return $this->workflow->initialize('order_product_classify');
    }

    private function _workflow_order_product_board () {
        $this->load->library('workflow/workflow');
        $this->load->model('order/order_product_board_model');
        return $this->workflow->initialize('order_product_board');
    }
}
