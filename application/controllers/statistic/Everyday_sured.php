<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月19日
 * @author Administrator
 * @version
 * @des
 * 每日确认
 */
class Everyday_sured extends MY_Controller {
    private $__Search = array(
        'day' => ''
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Everyday_asure/Everyday_asure __construct Start!');
        $this->load->model('order/order_model');
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
        if(!!($Return = $this->order_model->select_everyday_sured($this->_Search))){
            $Sum = 0;
            $Thick = 0;
            foreach ($Return as $key => $value){
                $Sum += $value['sum'];
                $Thick += $value['thick'];
            }
            $Num = count($Return);
            $Return[] = array(
                'num' => '共'. $Num . '单',
                'dealer' => '',
                'owner' => '总计',
                'sure' => '',
                'sure_datetime' => '',
                'sum' => $Sum,
                'thick' => $Thick
            );
            unset($Sum, $Thick);
            $Data['content'] = $Return;
            $Data['num'] = count($Return);
            $Data['p'] = ONE;
            $Data['pn'] = ONE;
            $Data['pagesize'] = ALL_PAGESIZE;
        }else{
            $this->Message = '对不起, 没有符合条件的内容!';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    public function customer_statistic () {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Days = $this->input->get('days', true);
        if (empty($Days)) {
            $this->_Search['start_date'] = date('Y-m-d');
        } else {
            $Days = '-' . $Days . ' days';
            $this->_Search['start_date'] = date('Y-m-d', strtotime($Days));
        }
        $this->_Search['end_date'] = date('Y-m-d', strtotime('+1 day'));
        $Data = array('list' => array());
        if (!!($Query = $this->order_model->select_everyday_sured($this->_Search))) {
            $Tmp = array();
            foreach ($Query as $Key => $Value) {
                if (!isset($Tmp[$Value['sure']])) {
                    $Tmp[$Value['sure']] = array(
                        'sum' => 0
                    );
                }
                $Tmp[$Value['sure']]['sum'] += $Value['sum'];
            }
            foreach ($Tmp as $Key => $Value) {
                $Data['list'][] = array(
                    'name' => $Key,
                    'value' => $Value['sum']
                );
            }
        }
        $this->_ajax_return($Data);
    }
}
