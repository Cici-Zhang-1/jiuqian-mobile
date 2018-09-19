<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Lack detail Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Lack_detail extends MY_Controller {
    private $__Search = array(
        'v' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller order/Lack_detail __construct Start!');
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
        $Data = array();
        if(!($Data = $this->order_product_board_plate_model->select_scan_lack_detail($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['status'] == '正在扫描' && !empty($Value['scanner'])) {
                    $Value['status'] = '已扫描';
                }
                $Data['content'][$Key] = $Value;
            }
        }
        $this->_ajax_return($Data);
    }
}
