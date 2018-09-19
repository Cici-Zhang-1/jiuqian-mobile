<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dismantled detail Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Dismantled_detail extends MY_Controller {
    private $__Search = array(
        'start_date' => '',
        'end_date' => '',
        'dismantle' => ZERO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller statistic/Dismantled_detail __construct Start!');
        $this->load->model('order/order_product_board_model');
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['start_date']) && empty($this->_Search['end_date'])) {
            $this->_Search['start_date'] = date('Y-m-01');
            $this->_Search['end_date'] = date('Y-m-d', strtotime('+1 day'));
        } elseif (empty($this->_Search['start_date']) && !empty($this->_Search['end_date'])) {
            $this->_Search['start_date'] = date('Y-m-01', gh_to_sec($this->_Search['end_date']));
        }
        if ($this->_Search['start_date'] >= $this->_Search['end_date']) {
            $this->_Search['start_date'] = date('Y-m-01', gh_to_sec($this->_Search['end_date']));
        }
        if (empty($this->_Search['dismantle'])) {
            $this->_Search['dismantle'] = $this->session->userdata('uid');
        }
        $Data = array();
        if(!($Data['content'] = $this->order_product_board_model->select_dismantled($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $Data['num'] = count($Data['content']);
            $Data['p'] = ONE;
            $Data['pn'] = ONE;
            $Data['pagesize'] = ALL_PAGESIZE;
        }
        $this->_ajax_return($Data);
    }
}
