<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月16日
 * @author Administrator
 * @version
 * @des
 * 扫描
 */
class Scan extends MY_Controller{
    private $__Search = array(
        'scan' => 0,
        'start_date' => '',
        'end_date' => '',
        'status' => WP_SCAN
    );
    private $_ScanGroupMethod = 0;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller order/Sscan __construct Start!');
        $this->load->model('order/scan_model');
        $this->load->model('data/configs_model');
        $this->_ScanGroupMethod = intval($this->configs_model->select_by_name('scan_group_method')); // 分组方法
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['scan'])) {
            if ($this->_is_scan_group()) {
                $this->_Search['scan'] = $this->session->userdata('uid');
            }
        }
        $Data = array();
        if(!($Data = $this->scan_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取扫描任务信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_return($Data);
    }

    public function edit(){
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Type = $this->input->post('type', true);
            if ($Type == ZERO) {
                // $this->_edit_order_product_classify();
                $this->Message = '这个订单包含二维码, 请通过扫描的方式确认扫描';
            } else {
                $this->_edit_order_product_board();
            }

        }
        $this->_ajax_return();
    }
    private function _edit_order_product_classify () {
        $Post = gh_escape($_POST);
        $this->load->model('order/order_product_classify_model');
        if (!!($Scan = $this->order_product_classify_model->is_status_and_brothers($Post['v'], array(WP_SCAN, WP_SCANNING)))) {
            $GLOBALS['workflow_msg'] = '';
            foreach ($Scan as $Key => $Value) {
                $GLOBALS['workflow_msg'] .= $Value['board'];
                $Scan[$Key] = $Value['v'];
            }
            $this->load->model('order/order_product_board_plate_model');
            if ($this->order_product_board_plate_model->are_scanned($Scan, true)) {
                $this->Code = EXIT_ERROR;
                $this->Message = '您的选择项中存在没有扫描的板块，因此不能确认扫描!';
            } else {
                $W = $this->_workflow_order_product_classify();
                if(!!($W->initialize($Scan))) {
                    $W->scanned();
                    $this->Message = '确认扫描成功, 刷新后生效!';
                    return true;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '您选择确认的已经确认扫描，不能重复确认';
        }
        return false;
    }
    private function _edit_order_product_board () {
        $Post = gh_escape($_POST);
        $this->load->model('order/order_product_board_model');
        if (!!($Scan = $this->order_product_board_model->is_status_and_brothers($Post['v'], array(WP_SCAN, WP_SCANNING)))) {
            $GLOBALS['workflow_msg'] = '';
            foreach ($Scan as $Key => $Value) {
                $GLOBALS['workflow_msg'] .= $Value['board'];
                $Scan[$Key] = $Value['v'];
            }
            $W = $this->_workflow_order_product_board();
            if(!!($W->initialize($Scan))) {
                $W->scanned();
                $this->Message = '确认扫描成功, 刷新后生效!';
                return true;
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = $W->get_failue();
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '您选择确认的已经确认扫描，不能重复确认';
        }
        return false;
    }

    private function _workflow_order_product_classify () {
        $this->load->library('workflow/workflow');
        return $this->workflow->initialize('order_product_classify');
    }
    private function _workflow_order_product_board () {
        $this->load->library('workflow/workflow');
        return $this->workflow->initialize('order_product_board');
    }

    public function correct () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            if ($this->_is_user()) {
                $Type = $this->input->post('type', true);
                if ($Type == ZERO) {
                    $this->_correct_order_product_classify();
                } else {
                    $this->_correct_order_product_board();
                }
            }
        }
        $this->_ajax_return();
    }
    private function _is_user () {
        $Scan = gh_escape($_POST['scan']);
        $this->load->model('manage/user_model');
        if (!!($this->_User = $this->user_model->is_exist($Scan))) {
            return true;
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '用户不存在!';
            return false;
        }
    }

    private function _correct_order_product_classify () {
        $Post = gh_escape($_POST);
        $this->load->model('order/order_product_classify_model');
        if (!!($Scan = $this->order_product_classify_model->are_scanned_and_brothers($Post['v']))) {
            foreach ($Scan as $Key => $Value) {
                $Scan[$Key] = $Value['v'];
            }
            $W = $this->_workflow_order_product_classify();
            if ($W->initialize($Scan)) {
                $W->set_data(array('scan' => $Post['scan']));
                $W->store_message('扫描矫正到' . $this->_User['truename']);
                $this->Message = '扫描矫正成功, 刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '扫描矫正失败!';
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '您选择扫描校正的订单还未确认扫描，不能校正';
        }
    }
    private function _correct_order_product_board () {
        $Post = gh_escape($_POST);
        $this->load->model('order/order_product_board_model');
        if (!!($Scan = $this->order_product_board_model->are_scanned_and_brothers($Post['v']))) {
            foreach ($Scan as $Key => $Value) {
                $Scan[$Key] = $Value['v'];
            }
            $W = $this->_workflow_order_product_board();
            if ($W->initialize($Scan)) {
                $W->set_data(array('scan' => $Post['scan']));
                $W->store_message('扫描矫正到' . $this->_User['truename']);
                $this->Message = '扫描矫正成功, 刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '扫描矫正失败!';
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '您选择扫描校正的订单还未确认扫描，不能校正';
        }
    }

    private function _is_scan_group () {
        $this->load->model('permission/usergroup_model');
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('扫描'))) {
            return $this->session->userdata('ugid') == $UsergroupV;
        }
        return false;
    }
}
