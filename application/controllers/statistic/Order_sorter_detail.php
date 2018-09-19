<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order sorter detail Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Order_sorter_detail extends MY_Controller {
    private $__Search = array(
        'start_date' => '',
        'end_date' => '',
        'dealer_id' => ZERO
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller statistic/Order_sorter_detail __construct Start!');
        $this->load->model('order/order_model');
    }

    public function read () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['dealer_id'])) {
            $this->_Search['dealer_id'] = $this->input->get('v', true);
            $this->_Search['dealer_id'] = intval(trim($this->_Search['dealer_id']));
        }
        $Data = array();
        if (empty($this->_Search['dealer_id'])) {
            $this->Message = '请选择客户查看下单明细!';
            $this->Code = EXIT_ERROR;
        } else {
            if(!($Query = $this->order_model->select_order_sorter($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            } else {
                foreach ($Query as $key => $value){
                    $value = array_merge($value, json_decode($value['sum_detail'], true));
                    $Query[$key] = $value;
                }
                $Data['content'] = $Query;
                $Data['num'] = count($Data['content']);
                $Data['pn'] = ONE;
                $Data['p'] = ONE;
                $Data['pagesize'] = ALL_PAGESIZE;
            }
        }
        $this->_ajax_return($Data);
    }
}
