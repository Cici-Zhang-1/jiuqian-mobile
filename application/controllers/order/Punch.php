<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月16日
 * @author Administrator
 * @version
 * @des
 * 打孔
 */
class Punch extends MY_Controller{
    private $__Search = array(
        'puncher' => 0,
        'start_date' => '',
        'end_date' => '',
        'status' => WP_PUNCH,
        'procedure' => P_PUNCH
    );
    private $_Classify = array();
    private $_Board = array();
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller order/Punch __construct Start!');
        $this->load->model('order/punch_model');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_' . $View)){
            $View = '_' . $View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['puncher'])) {
            if ($this->_is_punch_group()) {
                $this->_Search['puncher'] = $this->session->userdata('uid');
            }
        }
        $Data = array();
        if(!($Data = $this->punch_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    public function edit(){
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }

        if ($this->_do_form_validation()) {
            $this->_parse_type();
            $this->_edit_order_product_classify();
            $this->_edit_order_product_board();
        }
        $this->_ajax_return();
    }
    private function _edit_order_product_classify () {
        if (!empty($this->_Classify)) {
            $this->load->model('order/order_product_classify_model');
            if (!!($Query = $this->order_product_classify_model->is_status_and_brothers($this->_Classify, WP_PUNCH, P_PUNCH))) {
                $GLOBALS['workflow_msg'] = '';
                foreach ($Query as $Key => $Value) {
                    $GLOBALS['workflow_msg'] .= $Value['board'];
                    $Query[$Key] = $Value['v'];
                }
                $W = $this->_workflow_order_product_classify();
                $W->initialize($Query);
                if(!!($W->punched())) {
                    $this->Message = '确认打孔成功, 刷新后生效!';
                    return true;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选择确认的已经确认打孔，不能重复确认';
            }
            return false;
        }
        return true;
    }
    private function _edit_order_product_board () {
        if (!empty($this->_Board)) {
            $this->load->model('order/order_product_board_model');
            if (!!($Query = $this->order_product_board_model->is_status_and_brothers($this->_Board, WP_PUNCH, P_PUNCH))) {
                $GLOBALS['workflow_msg'] = '';
                foreach ($Query as $Key => $Value) {
                    $GLOBALS['workflow_msg'] .= $Value['board'];
                    $Query[$Key] = $Value['v'];
                }
                $W = $this->_workflow_order_product_board();
                if(!!($W->initialize($Query))) {
                    $W->punched();
                    $this->Message = '确认打孔成功, 刷新后生效!';
                    return true;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选择确认的已经确认打孔，不能重复确认';
            }
            return false;
        }
        return true;
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
    private function _parse_type () {
        $V = $this->input->post('v');
        $Relate = $this->input->post('relate', true);
        foreach ($V as $Key => $Value) {
            if ($Relate[$Key]['type'] == ZERO) {
                array_push($this->_Classify, $Value);
            } else {
                array_push($this->_Board, $Value);
            }
        }
    }
    private function _is_punch_group () {
        $this->load->model('permission/usergroup_model');
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('打孔'))) {
            return $this->session->userdata('ugid') == $UsergroupV;
        }
        return false;
    }
}
