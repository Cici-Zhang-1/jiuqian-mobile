<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月18日
 * @author Zhangcc
 * @version
 * @des
 * 包装统计
 */
class Packed_statistic extends MY_Controller{
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Pack_statistics Start!');
        $this->load->model('order/order_product_model');
    }

    public function read(){
        $this->get_page_search();
        $Data = array();
        if(!!($Data = $this->order_product_model->select_packed($this->_Search))){
            $this->load->helper('json_helper');
            foreach ($Data['content'] as $Key => $Value) {
                $Value['pack_detail'] = discode_pack($Value['pack_detail']);
                $Data['content'][$Key] = $Value;
            }
        } else {
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有对应的包装统计';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
}
