<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Lack Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Lack extends MY_Controller {
    private $__Search = array(
        'order_type' => 'X',
        'start_date' => '',
        'lack' => FIVE
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Lack __construct Start!');
        $this->load->model('order/order_product_board_plate_model');
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
            $this->_Search['start_date'] = date('Y-m-d', strtotime('-6 months'));
        }
        $Data = array();
        if(!($Data = $this->order_product_board_plate_model->select_scan_lack($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
}
