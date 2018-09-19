<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13
 * Time: 15:41
 */
class Scan_list extends MY_Controller {
    private $__Search = array(
        'start_date' => '',
        'status' => NO
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Pack Start!');
        $this->load->model('order/scan_model');
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['start_date'])) {
            $this->_Search['start_date'] = date('Y-m-d', strtotime('-6 months'));
        }
        $Data = array();
        if(!($Data = $this->scan_model->select_scan_list($this->_Search))){
            $this->Code = EXIT_ERROR;
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的扫描列表';
        } else {
            if ($this->_Search['status']) {
                $Status = '已扫描';
            } else {
                $Status = '正在扫描';
            }
            foreach ($Data['content'] as $Key => $Value) {
                $Value['status'] = $Status;
                $Data['content'][$Key] = $Value;
            }
        }
        $this->_ajax_return($Data);
    }
}