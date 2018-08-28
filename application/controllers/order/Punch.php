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
        'status' => OPB_PUNCH
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller order/Punch __construct Start!');
        $this->load->model('order/order_product_board_model');
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
            $this->_Search['puncher'] = $this->session->userdata('uid');
        }
        $Data = array();
        if(!($Data = $this->order_product_board_model->select_produce_process($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
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
            $Post = gh_escape($_POST);
            if (!!($Punch = $this->order_product_board_model->is_status_and_brothers($Post['v'], OPB_PUNCH))) {
                $GLOBALS['workflow_msg'] = '';
                foreach ($Punch as $Key => $Value) {
                    $GLOBALS['workflow_msg'] .= $Value['board'];
                    $Punch[$Key] = $Value['v'];
                }
                $this->load->library('workflow/workflow_order_product_board/workflow_order_product_board');
                if(!!($this->workflow_order_product_board->initialize($Punch))) {
                    $this->workflow_order_product_board->punched();
                    $this->Message = '确认打孔成功, 刷新后生效!';
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $this->workflow_order_product_board->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '您选择确认的已经确认打孔，不能重复确认';
            }
        }
        $this->_ajax_return();
    }
}
