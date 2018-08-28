<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Print list Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Print_list extends MY_Controller {
    private $__Search = array(
        'status' => NO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Print_list __construct Start!');
        $this->load->model('order/print_list_model');
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
        if(!($Data = $this->print_list_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
}
