<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Produce process tracking Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Produce_process_tracking extends MY_Controller {
    private $__Search = array(
        'order_type' => 'X',
        'warn_date' => FIVE
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Produce_process_tracking __construct Start!');
        $this->load->model('order/mrp_model');
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
        $this->_Search['warn_date'] = date('Y-m-d', strtotime('-' . $this->_Search['warn_date'] . ' days'));
        $Data = array();
        if(!($Data = $this->mrp_model->select_produce_process_tracking($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
}
