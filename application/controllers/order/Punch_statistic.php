<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月16日
 * @author Administrator
 * @version
 * @des
 * 打孔
 */
class Punch_statistic extends MY_Controller{
    private $__Search = array(
        'puncher' => 0,
        'start_date' => '',
        'end_date' => '',
        'status' => WP_PUNCHED
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller order/Punch_statistic __construct Start!');
        $this->load->model('order/order_product_board_model');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_' . $View)){
            $View = '_' . $View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['puncher'])) {
            $this->_Search['puncher'] = $this->session->userdata('uid');
        }
        if ($this->_Search['start_date'] == '') {
            $this->_Search['start_date'] = date('Y-m-01');
        }
        $Data = array();
        if(!($Data = $this->order_product_board_model->select_produce_process($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $X = array();
            $BOrderProductNum = array();
            $BArea = 0;
            $BAmount = 0;
            foreach ($Data['content'] as $Key => $Value) {
                if ($Value['order_type'] == 'X') {
                    if (!isset($X[$Value['product']])) {
                        $X[$Value['product']] = array(
                            'order_product_num' => array(),
                            'area' => 0,
                            'amount' => 0
                        );
                    }
                    $X[$Value['product']]['area'] = bcadd($X[$Value['product']]['area'], $Value['area'], 3);
                    // $X[$Value['product']]['area'] += $Value['area'];
                    if (!in_array($Value['order_product_num'], $X[$Value['product']]['order_product_num'])) {
                        array_push($X[$Value['product']]['order_product_num'], $Value['order_product_num']);
                        $X[$Value['product']]['amount']++;
                        if ($X[$Value['product']]['amount'] % 5 == 0) {
                            array_push($X[$Value['product']]['order_product_num'], '<br />');
                        }
                    }
                } elseif ($Value['order_type'] == 'B') {
                    $BArea = bcadd($BArea, $Value['area'], 3);
                    // $BArea += $Value['area'];
                    if (!in_array($Value['order_product_num'], $BOrderProductNum)) {
                        array_push($BOrderProductNum, $Value['order_product_num']);
                        $BAmount++;
                        if ($BAmount % 5 == 0) {
                            array_push($BOrderProductNum, '<br />');
                        }
                    }
                }
            }
            $Data['content'] = array();
            if (count($X) > 0) {
                foreach ($X as $Key => $Value) {
                    array_push($Data['content'], array(
                        'label' => $Key . '正常单',
                        'name' => implode(',', $Value['order_product_num']),
                        'area' => $Value['area'],
                        'amount' => $Value['amount']
                    ));
                }
            }
            if (count($BOrderProductNum) > 0) {
                array_push($Data['content'], array(
                    'label' => '补单',
                    'name' => implode(',', $BOrderProductNum),
                    'area' => $BAmount,
                    'amount' => $BArea
                ));
            }
            $Data['num'] = count($Data['content']);
            $Data['p'] = ONE;
            $Data['pn'] = ONE;
            $Data['pagesize'] =ALL_PAGESIZE;
        }
        $this->_return($Data);
    }
}
