<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Workflow mrp msg Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Workflow_mrp_msg extends MY_Controller {
    private $__Search = array(
        'v' => 0,
        'paging' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller workflow/Workflow_mrp_msg __construct Start!');
        $this->load->model('workflow/workflow_mrp_msg_model');
    }

    /**
    *
    * @return void
    */
    public function index() {
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->workflow_mrp_msg_model->select($this->_Search))) {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
}
