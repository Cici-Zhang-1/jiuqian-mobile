<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月14日
 * @author Zhangcc
 * @version
 * @des
 */
class Board_predict extends MY_Controller {
    private $__Search = array(
        'month' => ''
    );
    private $_Predict = array(
        'cabinet' => array(
            0,0
        ),
        'wardrobe' => array(
            0,0
        ),
        'door' => array(
            0,0
        ),
        'wood' => array(
            0,0
        ),
        'area' => array(
            0,0
        )
    );
    private $_Predict1 = array(); // 确认后
    private $_Predict2 = array(); // 核价后
    private $_Predict3 = array(); // 确认后预计
    private $_Predict4 = array(); // 核价后预计
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Board_predict __construct Start!');
        $this->load->model('order/order_product_board_model');
        $this->_Predict1 = $this->_Predict;
        $this->_Predict2 = $this->_Predict;
        $this->_Predict3 = $this->_Predict;
        $this->_Predict4 = $this->_Predict;
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
        $this->_read_board_predict();
        $this->_wait_sure_predict();
        $this->_produce_predict();
        $Predict0 = array(
            'cabinet' => '橱柜[平方]',
            'wardrobe' => '衣柜[平方]',
            'door' => '门板[平方]',
            'wood' => '木框门[平方]',
            'area' => '总计[平方]'
        );
        $Data = array();
        $Tmp = array();
        foreach ($Predict0 as $key => $value){
            $Tmp[] = array(
                'name' => $value,
                'wait_sure' => implode('/', $this->_Predict1[$key]),
                'wait_sure_predict' => implode('/', $this->_Predict3[$key]),
                'produce' => implode('/', $this->_Predict2[$key]),
                'produce_predict' => implode('/', $this->_Predict4[$key])
            );
        }
        $Data['content'] = $Tmp;
        $Data['num'] = count($Tmp);
        $Data['p'] = ONE;
        $Data['pn'] = ONE;
        $Data['pagesize'] = ALL_PAGESIZE;
        $this->_ajax_return($Data);
    }

    private function _read_board_predict () {
        if ($Query = $this->order_product_board_model->select_board_predict($this->_Search)) {
            foreach ($Query as $Key => $Value) {
                if ($Value['status'] > O_WAIT_SURE) {
                    $this->_read_produce($Value);
                } else {
                    $this->_read_wait_sure($Value);
                }
            }
        }
    }
    private function _read_wait_sure ($Board) {
        if ($Board['thick'] > THICK) {
            switch ($Board['product_id']) {
                case CABINET:
                    $this->_Predict1['cabinet'][ZERO] += $Board['area'];
                    break;
                case WARDROBE:
                    $this->_Predict1['wardrobe'][ZERO] += $Board['area'];
                    break;
                case DOOR:
                    $this->_Predict1['door'][ZERO] += $Board['area'];
                    break;
                case WOOD:
                    $this->_Predict1['wood'][ZERO] += $Board['area'];
                    break;
            }
            $this->_Predict1['area'][ZERO] += $Board['area'];
        } else {
            switch ($Board['product_id']) {
                case CABINET:
                    $this->_Predict1['cabinet'][ONE] += $Board['area'];
                    break;
                case WARDROBE:
                    $this->_Predict1['wardrobe'][ONE] += $Board['area'];
                    break;
                case DOOR:
                    $this->_Predict1['door'][ONE] += $Board['area'];
                    break;
                case WOOD:
                    $this->_Predict1['wood'][ONE] += $Board['area'];
                    break;
            }
            $this->_Predict1['area'][ONE] += $Board['area'];
        }
    }

    private function _wait_sure_predict () {
        $this->_Predict3 = $this->_Predict1;
        foreach ($this->_Predict1 as $key => $value){
            foreach ($value as $Ikey => $Ivalue) {
                $this->_Predict3[$key][$Ikey] = round(($Ivalue/$this->_Search['current_day'])*$this->_Search['days']*M_REGULAR)/M_REGULAR;
            }
        }
        return $this->_Predict3;
    }

    private function _read_produce ($Board) {
        if ($Board['thick'] > THICK) {
            switch ($Board['product_id']) {
                case CABINET:
                    $this->_Predict2['cabinet'][ZERO] += $Board['area'];
                    break;
                case WARDROBE:
                    $this->_Predict2['wardrobe'][ZERO] += $Board['area'];
                    break;
                case DOOR:
                    $this->_Predict2['door'][ZERO] += $Board['area'];
                    break;
                case WOOD:
                    $this->_Predict2['wood'][ZERO] += $Board['area'];
                    break;
            }
            $this->_Predict2['area'][ZERO] += $Board['area'];
        } else {
            switch ($Board['product_id']) {
                case CABINET:
                    $this->_Predict2['cabinet'][ONE] += $Board['area'];
                    break;
                case WARDROBE:
                    $this->_Predict2['wardrobe'][ONE] += $Board['area'];
                    break;
                case DOOR:
                    $this->_Predict2['door'][ONE] += $Board['area'];
                    break;
                case WOOD:
                    $this->_Predict2['wood'][ONE] += $Board['area'];
                    break;
            }
            $this->_Predict2['area'][ONE] += $Board['area'];
        }
    }

    private function _produce_predict () {
        $this->_Predict4 = $this->_Predict;
        foreach ($this->_Predict2 as $key => $value) {
            foreach ($value as $Ikey => $Ivalue) {
                $this->_Predict4[$key][$Ikey] = round(($Ivalue/$this->_Search['current_day'])*$this->_Search['days']*M_REGULAR)/M_REGULAR;
            }
        }
        return $this->_Predict4;
    }
}