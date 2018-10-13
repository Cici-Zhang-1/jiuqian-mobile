<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月16日
 * @author Administrator
 * @version
 * @des
 * 封边
 */
class Edge extends MY_Controller{
    private $__Search = array(
        'edge' => 0,
        'start_date' => '',
        'end_date' => '',
        'status' => WP_EDGE
    );
    private $_EdgeAutoGet;
    private $_User;
    private $_Classify = array();
    private $_Board = array();
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller order/Edge __construct Start!');
        $this->load->model('order/edge_model');
        $this->load->model('data/configs_model');
        $this->_EdgeAutoGet = intval($this->configs_model->select_by_name('edge_auto_get')); // 分组方法
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
        if (empty($this->_Search['edge'])) {
            if ($this->_is_edge_group()) {
                $this->_Search['edge'] = $this->session->userdata('uid');
            }
        }
        $Data = array();
        if(!($Data = $this->edge_model->select($this->_Search))){
            if ($this->_Search['status'] == WP_EDGING && $this->_EdgeAutoGet) {
                if(!($this->_next()) || !($Data = $this->edge_model->select($this->_Search))){
                    $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                    $this->Code = EXIT_ERROR;
                }
            } else {
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_return($Data);
    }

    /**
     * 获得下一次封边
     */
    private function _next() {
        $this->load->model('permission/usergroup_model');
        $this->load->model('manage/user_model');
        $User = $this->user_model->is_exist($this->session->userdata('uid'));
        if ($User['status']) {
            if ($this->_is_edge_group()) {
                if(!($Data = $this->edge_model->select_next_edge())) {
                    $this->Message = '读取信息失败';
                } else {
                    $Workflow = array();
                    $Type = ZERO;
                    foreach ($Data as $Key => $Value) {
                        array_push($Workflow, $Value['v']);
                        $Type = $Value['type'];
                    }
                    if ($Type == ZERO) {
                        $W = $this->_workflow_order_product_classify();
                    } else {
                        $W = $this->_workflow_order_product_board();
                    }
                    if ($W->initialize($Workflow)) {
                        $W->edging();
                        return true;
                    } else {
                        $this->Message = $W->get_failue();
                    }
                }
            } else {
                $this->Message = '当前登录用户不属于封边组';
            }
        } else {
            $this->Message = '当前用户不在工作状态!';
        }
        return false;
    }
    private function _workflow_order_product_classify () {
        $this->load->library('workflow/workflow');
        return $this->workflow->initialize('order_product_classify');
    }
    private function _workflow_order_product_board () {
        $this->load->library('workflow/workflow');
        $this->load->model('order/order_product_board_model');
        return $this->workflow->initialize('order_product_board');
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
            if (!!($Edge = $this->order_product_classify_model->is_status_and_brothers($this->_Classify, $this->_EdgeAutoGet ? WP_EDGING : WP_EDGE))) {
                $GLOBALS['workflow_msg'] = '';
                foreach ($Edge as $Key => $Value) {
                    $GLOBALS['workflow_msg'] .= $Value['board'];
                    $Edge[$Key] = $Value['v'];
                }
                $W = $this->_workflow_order_product_classify();
                $W->initialize($Edge);
                if(!!($W->edged())) {
                    $this->Message = '确认封边成功, 刷新后生效!';
                    return true;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选择确认的已经确认封边，不能重复确认';
            }
            return false;
        }
        return true;
    }
    private function _edit_order_product_board () {
        if (!empty($this->_Board)) {
            $this->load->model('order/order_product_board_model');
            if (!!($Edge = $this->order_product_board_model->is_status_and_brothers($this->_Board, $this->_EdgeAutoGet ? WP_EDGING : WP_EDGE))) {
                $GLOBALS['workflow_msg'] = '';
                foreach ($Edge as $Key => $Value) {
                    $GLOBALS['workflow_msg'] .= $Value['board'];
                    $Edge[$Key] = $Value['v'];
                }
                $W = $this->_workflow_order_product_board();
                if(!!($W->initialize($Edge))) {
                    $W->edged();
                    $this->Message = '确认封边成功, 刷新后生效!';
                    return true;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选择确认的已经确认封边，不能重复确认';
            }
            return false;
        }
        return true;
    }

    public function correct () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            if ($this->_is_user()) {
                /*$Type = $this->input->post('type', true);
                if ($Type == ZERO) {
                    $this->_correct_order_product_classify();
                } else {
                    $this->_correct_order_product_board();
                }*/
                $this->_parse_type();
                $this->_correct_order_product_classify();
                $this->_correct_order_product_board();
            }
        }
        $this->_ajax_return();
    }
    private function _is_user () {
        $Edge = gh_escape($_POST['edge']);
        $this->load->model('manage/user_model');
        if (!!($this->_User = $this->user_model->is_exist($Edge))) {
            return true;
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '用户不存在!';
            return false;
        }
    }

    private function _correct_order_product_classify () {
        // $Post = gh_escape($_POST);
        if (!empty($this->_Classify)) {
            $Post = gh_escape($_POST);
            $this->load->model('order/order_product_classify_model');
            if (!!($Edge = $this->order_product_classify_model->are_edged_and_brothers($this->_Classify, WP_EDGED))) {
                foreach ($Edge as $Key => $Value) {
                    $Edge[$Key] = $Value['v'];
                }
                $W = $this->_workflow_order_product_classify();
                if ($W->initialize($Edge)) {
                    $W->set_data(array('edge' => $Post['edge']));
                    $W->store_message('封边矫正到' . $this->_User['truename']);
                    $this->Message = '封边矫正成功, 刷新后生效!';
                    return true;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '封边矫正失败!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选择封边校正的订单还未确认封边，不能校正';
            }
            return false;
        }
        return true;
    }
    private function _correct_order_product_board () {
        if (!empty($this->_Board)) {
            $Post = gh_escape($_POST);
            $this->load->model('order/order_product_board_model');
            if (!!($Edge = $this->order_product_board_model->are_edged_and_brothers($Post['v'], WP_EDGED))) {
                foreach ($Edge as $Key => $Value) {
                    $Edge[$Key] = $Value['v'];
                }
                $W = $this->_workflow_order_product_board();
                if ($W->initialize($Edge)) {
                    $W->set_data(array('edge' => $Post['edge']));
                    $W->store_message('封边矫正到' . $this->_User['truename']);
                    $this->Message = '封边矫正成功, 刷新后生效!';
                    return true;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '封边矫正失败!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选择封边校正的订单还未确认封边，不能校正';
            }
            return false;
        }
        return true;
    }

    /**
     * 解析操作类型
     */
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
    private function _is_edge_group () {
        $this->load->model('permission/usergroup_model');
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('封边'))) {
            return $this->session->userdata('ugid') == $UsergroupV;
        }
        return false;
    }
}
