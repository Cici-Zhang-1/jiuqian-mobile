<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 2016年3月16日
 * @author Administrator
 * @version
 * @des
 * 打包
 */
class Pack extends MY_Controller{
    private $__Search = array(
        'pack' => 0,
        'start_date' => '',
        'end_date' => '',
        'status' => WP_PACK
    );
    private $_PackGroupMethod = 0;
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Ppack __construct Start!');
        $this->load->model('order/pack_model');
        $this->load->model('data/configs_model');
        $this->_PackGroupMethod = intval($this->configs_model->select_by_name('pack_group_method')); // 分组方法
    }

    public function index() {
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_' . $View)){
            $View = '_' . $View;
            $this->$View();
        } else {
            $this->_index($View);
        }
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['pack'])) {
            if ($this->_is_pack_group()) {
                $this->_Search['pack'] = $this->session->userdata('uid');
            }
        }
        if ($this->_Search['status'] == WP_PACKED && $this->_Search['start_date'] == '') {
            $this->_Search['start_date'] = date('Y-m-01');
        }
        $Data = array();
        if(!($Data = $this->pack_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取打包任务信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_return($Data);
    }

    public function correct () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            if ($this->_is_user()) {
                $this->_parse_type();
                $this->_correct_order_product_classify();
                $this->_correct_order_product_board();
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
        if (!empty($this->_Classify)) {
            $Post = gh_escape($_POST);
            $this->load->model('order/order_product_classify_model');
            if (!!($Scan = $this->order_product_classify_model->are_packed_and_brothers($this->_Classify))) {
                foreach ($Scan as $Key => $Value) {
                    $Scan[$Key] = $Value['v'];
                }
                $W = $this->_workflow_order_product_classify();
                if ($W->initialize($Scan)) {
                    $W->set_data(array('pack' => $Post['pack']));
                    $W->store_message('打包矫正到' . $this->_User['truename']);
                    $this->Message = '打包矫正成功, 刷新后生效!';
                    return true;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '打包矫正失败!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选择打包校正的订单还未确认打包，不能校正';
            }
            return false;
        }
        return true;
    }
    private function _correct_order_product_board () {
        if (!empty($this->_Board)) {
            $Post = gh_escape($_POST);
            $this->load->model('order/order_product_board_model');
            if (!!($Scan = $this->order_product_board_model->are_packed_and_brothers($this->_Board))) {
                foreach ($Scan as $Key => $Value) {
                    $Scan[$Key] = $Value['v'];
                }
                $W = $this->_workflow_order_product_board();
                if ($W->initialize($Scan)) {
                    $W->set_data(array('pack' => $Post['pack']));
                    $W->store_message('打包矫正到' . $this->_User['truename']);
                    $this->Message = '打包矫正成功, 刷新后生效!';
                    return true;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '打包矫正失败!';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选择打包校正的订单还未确认打包，不能校正';
            }
            return false;
        }
        return true;
    }

    private function _workflow_order_product_classify () {
        $this->load->library('workflow/workflow');
        return $this->workflow->initialize('order_product_classify');
    }
    private function _workflow_order_product_board () {
        $this->load->library('workflow/workflow');
        return $this->workflow->initialize('order_product_board');
    }

    private function _parse_type () {
        $V = $this->input->post('v');
        $Relate = $this->input->post('relate', true);
        if (empty($Relate)) {
            $Type = $this->input->post('type', true);
        }
        foreach ($V as $Key => $Value) {
            if (empty($Relate)) {
                if ($Type == ZERO) {
                    array_push($this->_Classify, $Value);
                } else {
                    array_push($this->_Board, $Value);
                }
            } else {
                if ($Relate[$Key]['type'] == ZERO) {
                    array_push($this->_Classify, $Value);
                } else {
                    array_push($this->_Board, $Value);
                }
            }
        }
    }
    private function _is_pack_group () {
        $this->load->model('permission/usergroup_model');
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('打包'))) {
            return $this->session->userdata('ugid') == $UsergroupV;
        }
        return false;
    }
}