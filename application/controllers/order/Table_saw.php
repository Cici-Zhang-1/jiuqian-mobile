<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月16日
 * @author Administrator
 * @version
 * @des
 * 电子锯
 */
class Table_saw extends MY_Controller{
    private $__Search = array(
        'saw' => 0,
        'start_date' => '',
        'end_date' => '',
        'status' => WP_ELECTRONIC_SAW,
        'procedure' => P_TABLE_SAW
    );
    private $_Board;

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Talble_saw Start!');
        $this->load->model('order/table_saw_model');
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
        if (empty($this->_Search['saw'])) {
            if ($this->_is_table_saw()) {
                $this->_Search['saw'] = $this->session->userdata('uid');
            }
        }
        $Data = array();
        if(!($Data = $this->table_saw_model->select($this->_Search))){
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
            $this->_Board = $this->input->post('v');
            $this->_edit_order_product_board();
        }
        $this->_ajax_return();
        /* $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Selected = $this->input->post('selected', true);
            $this->load->library('workflow/workflow');
            foreach ($Selected as $key => $value){
                $this->workflow->initialize('order_product_classify', $value);
                $this->workflow->table_saw();
            }
        }else{
            $this->Failue = validation_errors();
        }
        $this->_return(); */
    }
    private function _edit_order_product_board () {
        $this->load->model('order/order_product_board_model');
        if (!!($Query = $this->order_product_board_model->is_status_and_brothers($this->_Board, WP_ELECTRONIC_SAW, P_TABLE_SAW))) {
            $GLOBALS['workflow_msg'] = '';
            foreach ($Query as $Key => $Value) {
                $GLOBALS['workflow_msg'] .= $Value['board'];
                $Query[$Key] = $Value['v'];
            }
            $W = $this->_workflow_order_product_board();
            if(!!($W->initialize($Query))) {
                $W->electronic_sawed();
                $this->Message = '确认下料成功, 刷新后生效!';
                return true;
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = $W->get_failue();
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '您选择确认的已经确认下料，不能重复确认';
        }
        return false;
    }
    private function _workflow_order_product_board () {
        $this->load->library('workflow/workflow');
        $this->load->model('order/order_product_board_model');
        return $this->workflow->initialize('order_product_board');
    }
    private function _is_table_saw() {
        $this->load->model('permission/usergroup_model');
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('推台锯'))) {
            return $this->session->userdata('ugid') == $UsergroupV;
        } else {
            return false;
        }
    }
}
