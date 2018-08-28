<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 */
class Produce extends MY_Controller{
    private $Search = array(
        'status' => O_PRODUCE,
        'keyword' => '',
        'all' => NO,
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Produce Start!');
        $this->load->model('order/order_model');
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->order_model->select($this->_Search, '_produce'))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }

        $this->_ajax_return($Data);
    }
}
