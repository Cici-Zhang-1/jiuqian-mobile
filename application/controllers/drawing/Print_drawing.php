<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Print drawing Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Print_drawing extends MY_Controller {
    private $__Search = array(
        'order_product_id' => ZERO,
        'drawing_id' => ZERO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller drawing/Print_drawing __construct Start!');
        $this->load->model('drawing/drawing_model');
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
        if (empty($this->_Search['drawing_id'])) {
            $this->_Search['drawing_id'] = $this->input->get('v', true);
            $this->_Search['drawing_id'] = intval($this->_Search['drawing_id']);
        }
        $Data = array();
        if(!($Data = $this->drawing_model->select($this->_Search))) {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            foreach ($Data['content'] as $Key => $Value) {
                $Value['url'] = drawing_url($Value['path']);
                $Data['content'][$Key] = $Value;
            }
        }
        if (!empty($this->_Search['order_product_id'])) {
            $Data['query']['order_product_id'] = $this->_Search['order_product_id'];
        }
        if (!empty($this->_Search['drawing_id'])) {
            $Data['query']['drawing_id'] = $this->_Search['drawing_id'];
        }
        $this->_ajax_return($Data);
    }
}
