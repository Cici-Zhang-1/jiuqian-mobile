<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pack Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Package extends MY_Controller {
    private $__Search = array(
        'start_date' => '',
        'end_date' => '',
        'status' => SCANNING,
        'order_type' => 'X'
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Pack __construct Start!');
        $this->load->model('order/order_product_model');
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
        if (empty($this->_Search['start_date'])) {
            $this->_Search['start_date'] = date('Y-m-d', strtotime('-1 months'));
        }
        $Data = array();
        if(!($Data = $this->order_product_model->select_pack($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
}
