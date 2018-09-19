<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月27日
 * @author Zhangcc
 * @version
 * @des
 * 清理配件
 */
class Produce_fitting extends MY_Controller{
    private $__Search = array(
        'day' => ''
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Produce_fitting __construct Start!');
        $this->load->model('order/order_product_fitting_model');
    }

    public function index() {
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['day'])) {
            $this->_Search['start_date'] = date('Y-m-d');
            $this->_Search['end_date'] = date('Y-m-d', strtotime('+1 day'));
        } else {
            $this->_Search['start_date'] = $this->_Search['day'];
            $this->_Search['end_date'] = date('Y-m-d', gh_to_sec($this->_Search['day']) + DAYS);
        }
        $Data = array();
        if(!!($Data = $this->order_product_fitting_model->select_produce($this->_Search))){
            $Data['query']['start_date'] = $this->_Search['start_date'];
            $this->Message = '获取要打印配件订单成功!';
        } else {
            $this->Message .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的配件订单!';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
    
    private function _prints(){
        $V = $this->input->get('v', true);
        if (!is_array($V)) {
            $V = explode(',', $V);
        }
        foreach ($V as $key => $value){
            $V[$key] = intval(trim($value));
            if($V[$key] <= 0){
                unset($V[$key]);
            }
        }

        $StartDate = $this->input->get('start_date', true);
        if (!empty($V)) {
            if(!!($Query = $this->order_product_fitting_model->select_produce_by_order_product_id($V))){
                $Tmp = array();
                $First = array();
                foreach ($Query as $key => $value){
                    if(!isset($Tmp[$value['order_product_num']])){
                        $Tmp[$value['order_product_num']] = array(
                            'dealer' => $value['dealer'],
                            'child' => array()
                        );
                    }
                    $Tmp[$value['order_product_num']]['child'][] = array(
                        'name' => $value['name'],
                        'speci' => $value['speci'],
                        'amount' => $value['amount'],
                        'unit' => $value['unit'],
                        'remark' => $value['remark']
                    );
                    if ($value['status'] == WP_PRINT_LIST) {
                        array_push($First, $value['v']);
                    }
                }
                unset($Return);
                $Data['List'] = $Tmp;
                $Data['StartDate'] = $StartDate;
                $this->load->view('header2');
                $this->load->view($this->_Item.__FUNCTION__, $Data);
                if (!empty($First)) {
                    $this->_workflow($First);
                }
            }else{
                show_error('您要打印的配件订单不存在!');
            }
        } else {
            show_error('请先选择要打印的配件订单!');
        }
    }

    private function _workflow ($V) {
        $this->load->library('workflow/workflow');
        $W = $this->workflow->initialize('order_product_fitting');
        $W->initialize($V);
        if(!!($W->printed_list())){
            return true;
        }else{
            $this->Code = EXIT_ERROR;
            $this->Message = $W->get_failue();
            return false;
        }
    }
}
