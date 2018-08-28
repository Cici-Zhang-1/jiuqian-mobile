<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Unqrcode label Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Unqrcode_label extends MY_Controller {
    private $__Search = array(
        'v' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller warehouse/Unqrcode_label __construct Start!');
        $this->load->model('warehouse/unqrcode_model');
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
        if(!($Data = $this->unqrcode_model->select_for_label($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $Dealer = explode('_', $Data['dealer']);
            array_shift($Dealer);
            $Data['dealer'] = array_shift($Dealer);
        }
        $this->_ajax_return($Data);
    }
}
