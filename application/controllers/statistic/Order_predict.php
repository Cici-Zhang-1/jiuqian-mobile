<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月25日
 * @author Zhangcc
 * @version
 * @des
 * 销售预计
 */
class Order_predict extends MY_Controller {
    private $__Search = array(
        'month' => ''
    );
    private $_Predict = array(
        'cabinet' => 0,
        'wardrobe' => 0,
        'door' => 0,
        'wood' => 0,
        'fitting' => 0,
        'other' => 0,
        'server' => 0,
        'sum' => 0
    );
    private $_Predict1 = array(); // 确认后
    private $_Predict2 = array(); // 核价后
    private $_Predict3 = array(); // 确认后预计
    private $_Predict4 = array(); // 核价后预计
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order_predict/Order_predict __construct Start!');
        $this->load->model('order/order_model');
        $this->load->helper('array');
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['month'])) {
            $this->_Search['month'] = date('m');
            $this->_Search['year'] = date('Y');
            $this->_Search['current_day'] = date('d');
            $this->_Search['days'] = cal_days_in_month(CAL_GREGORIAN, $this->_Search['month'], $this->_Search['year']);
        } else {
            $Month = gh_to_sec($this->_Search['month']);
            $this->_Search['month'] = date('m', $Month);
            $this->_Search['year'] = date('Y', $Month);
            $this->_Search['days'] = cal_days_in_month(CAL_GREGORIAN, $this->_Search['month'], $this->_Search['year']);
            if ($this->_Search['year'] == date('Y') && $this->_Search['month'] == date('m')) {
                $this->_Search['current_day'] = date('d');
            } else {
                $this->_Search['current_day'] = $this->_Search['days'];
            }
        }
        $this->_Search['start_date'] = date('Y-m-d', mktime(0,0,0,$this->_Search['month'],1,$this->_Search['year']));
        $this->_Search['end_date'] = date('Y-m-d', mktime(0,0,0,$this->_Search['month'],$this->_Search['days']+1,$this->_Search['year']));
        $this->_read_wait_sure();
        $this->_read_produce();
        $this->_wait_sure_predict();
        $this->_produce_predict();
        $Predict0 = array(
            'cabinet' => '橱柜[￥]',
            'wardrobe' => '衣柜[￥]',
            'door' => '门板[￥]',
            'wood' => '木框门[￥]',
            'fitting' => '配件[￥]',
            'other' => '外购[￥]',
            'server' => '服务[￥]',
            'sum' => '总额[￥]'
        );
        $Data = array();
        $Tmp = array();
        foreach ($Predict0 as $key => $value){
            $Tmp[] = array(
                'name' => $value,
                'wait_sure' => $this->_Predict1[$key],
                'wait_sure_predict' => $this->_Predict3[$key],
                'produce' => $this->_Predict2[$key],
                'produce_predict' => $this->_Predict4[$key]
            );
        }
        $Data['content'] = $Tmp;
        $Data['num'] = count($Tmp);
        $Data['p'] = ONE;
        $Data['pn'] = ONE;
        $Data['pagesize'] = ALL_PAGESIZE;
        $this->_ajax_return($Data);
    }

    private function _read_wait_sure () {
        $this->_Predict1 = $this->_Predict;
        if ($Query = $this->order_model->select_after_wait_sure($this->_Search)) {
            $this->load->helper('array');
            foreach ($Query as $key => $value){
                $SumDetail = json_decode($value['sum_detail'], true);
                $SumDetail['sum'] = $value['sum'];
                $this->_Predict1 = element_sum($this->_Predict1, $SumDetail);
            }
        }
        return $this->_Predict1;
    }

    private function _wait_sure_predict () {
        $this->_Predict3 = $this->_Predict1;
        foreach ($this->_Predict1 as $key => $value){
            $this->_Predict3[$key] = round(($value/$this->_Search['current_day'])*$this->_Search['days']*M_REGULAR)/M_REGULAR;
        }
        return $this->_Predict3;
    }

    private function _read_produce () {
        $this->_Predict2 = $this->_Predict;
        if ($Query = $this->order_model->select_after_produce($this->_Search)) {
            foreach ($Query as $key => $value){
                if($value['sum'] > 0 && !empty($value['sum_detail'])){
                    $SumDetail = json_decode($value['sum_detail'], true);
                    $SumDetail['sum'] = $value['sum'];
                    $this->_Predict2 = element_sum($this->_Predict2, $SumDetail);
                }
            }
        }
        return $this->_Predict2;
    }

    private function _produce_predict () {
        $this->_Predict4 = $this->_Predict;
        foreach ($this->_Predict2 as $key => $value){
            $this->_Predict4[$key] = round(($value/$this->_Search['current_day'])*$this->_Search['days']*M_REGULAR)/M_REGULAR;
        }
        return $this->_Predict4;
    }
}