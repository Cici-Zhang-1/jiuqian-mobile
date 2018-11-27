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
    private $_Cabinet = array();
    private $_Wardrobe = array();
    private $_Door = array();
    private $_Wood = array();
    private $_Fitting = array();
    private $_Other = array();
    private $_Server = array();
    private $_Sum = array();
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
        if ($this->_Search['start_date'] == date('Y-m-01') && $this->_Search['end_date'] == date('Y-m-01', strtotime('+1 month'))) {
            $this->_Search['sample_start_date'] = date('Y-m-d', strtotime('-30 days'));
            $this->_Search['sample_end_date'] = date('Y-m-d');
        } else {
            $this->_Search['sample_start_date'] = $this->_Search['start_date'];
            $this->_Search['sample_end_date'] = $this->_Search['end_date'];
        }
        $this->_read_wait_sure();
        $this->_read_produce();
        $this->_read_sample_produce();
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

    private function _read_sample_produce () {
        if ($Query = $this->order_model->select_sample_after_produce($this->_Search)) {
            foreach ($Query as $key => $value){
                if($value['sum'] > 0 && !empty($value['sum_detail'])){
                    $SumDetail = json_decode($value['sum_detail'], true);
                    $SumDetail['sum'] = $value['sum'];
                    $Datetime = explode(' ', $value['sure_datetime']);
                    $Datetime = array_shift($Datetime);
                    if ($SumDetail['cabinet'] > ZERO) {
                        if (empty($this->_Cabinet[$Datetime])) {
                            $this->_Cabinet[$Datetime] = $SumDetail['cabinet'];
                        } else {
                            $this->_Cabinet[$Datetime] += $SumDetail['cabinet'];
                        }
                    }
                    if ($SumDetail['wardrobe'] > ZERO) {
                        if (empty($this->_Wardrobe[$Datetime])) {
                            $this->_Wardrobe[$Datetime] = $SumDetail['wardrobe'];
                        } else {
                            $this->_Wardrobe[$Datetime] += $SumDetail['wardrobe'];
                        }
                    }
                    if ($SumDetail['door'] > ZERO) {
                        if (empty($this->_Door[$Datetime])) {
                            $this->_Door[$Datetime] = $SumDetail['door'];
                        } else {
                            $this->_Door[$Datetime] += $SumDetail['door'];
                        }
                    }
                    if ($SumDetail['wood'] > ZERO) {
                        if (empty($this->_Wood[$Datetime])) {
                            $this->_Wood[$Datetime] = $SumDetail['wood'];
                        } else {
                            $this->_Wood[$Datetime] += $SumDetail['wood'];
                        }
                    }
                    if ($SumDetail['fitting'] > ZERO) {
                        if (empty($this->_Fitting[$Datetime])) {
                            $this->_Fitting[$Datetime] = $SumDetail['fitting'];
                        } else {
                            $this->_Fitting[$Datetime] += $SumDetail['fitting'];
                        }
                    }
                    if ($SumDetail['other'] > ZERO) {
                        if (empty($this->_Other[$Datetime])) {
                            $this->_Other[$Datetime] = $SumDetail['other'];
                        } else {
                            $this->_Other[$Datetime] += $SumDetail['other'];
                        }
                    }
                    if ($SumDetail['server'] > ZERO) {
                        if (empty($this->_Server[$Datetime])) {
                            $this->_Server[$Datetime] = $SumDetail['server'];
                        } else {
                            $this->_Server[$Datetime] += $SumDetail['server'];
                        }
                    }
                    if (empty($this->_Sum[$Datetime])) {
                        $this->_Sum[$Datetime] = $SumDetail['sum'];
                    } else {
                        $this->_Sum[$Datetime] += $SumDetail['sum'];
                    }
                }
            }
        }
        return true;
    }

    private function _produce_predict () {
        $this->_Predict4 = $this->_Predict;
        foreach ($this->_Predict2 as $key => $value){
            $this->_Predict4[$key] = round(($value/$this->_Search['current_day'])*$this->_Search['days']*M_REGULAR)/M_REGULAR;
        }
        /*$this->_Predict4['cabinet'] = $this->_produce_cabinet_predict();
        $this->_Predict4['wardrobe'] = $this->_produce_wardrobe_predict();
        $this->_Predict4['door'] = $this->_produce_door_predict();
        $this->_Predict4['wood'] = $this->_produce_wood_predict();
        $this->_Predict4['fitting'] = $this->_produce_fitting_predict();
        $this->_Predict4['other'] = $this->_produce_other_predict();
        $this->_Predict4['server'] = $this->_produce_server_predict();
        $this->_Predict4['sum'] = $this->_produce_sum_predict();*/
        return $this->_Predict4;
    }

    private function _produce_cabinet_predict () {
        $Mid = 0;
        $Num = count($this->_Cabinet);
        if ($Num > 0) {
            sort($this->_Cabinet);
            if ($Num >= THREE) {
                array_pop($this->_Cabinet);
                array_shift($this->_Cabinet);
                $Mid = array_sum($this->_Cabinet) / ($Num - TWO);
            } else {
                $Mid = array_sum($this->_Cabinet) / $Num;
            }
            /*if ($Num % TWO === 0) {
                $Mid = ($this->_Cabinet[$Num/TWO] + $this->_Controller[$Num/TWO + ONE])/TWO;
            } else {
                $Mid = $this->_Cabinet[($Num + 1) / TWO];
            }*/
        }
        $Mid = round(($Num/THIRTY)*$this->_Search['days'] * $Mid *M_REGULAR)/M_REGULAR;
        return $Mid;
    }
    private function _produce_wardrobe_predict () {
        $Mid = 0;
        $Num = count($this->_Wardrobe);
        if ($Num > 0) {
            sort($this->_Wardrobe);
            if ($Num >= THREE) {
                array_pop($this->_Wardrobe);
                array_shift($this->_Wardrobe);
                $Mid = array_sum($this->_Wardrobe) / ($Num - TWO);
            } else {
                $Mid = array_sum($this->_Wardrobe) / $Num;
            }
            /*if ($Num % TWO === 0) {
                $Mid = ($this->_Wardrobe[$Num/TWO] + $this->_Wardrobe[$Num/TWO + ONE])/TWO;
            } else {
                $Mid = $this->_Wardrobe[($Num + 1) / TWO];
            }*/
        }
        $Mid = round(($Num/THIRTY)*$this->_Search['days'] * $Mid *M_REGULAR)/M_REGULAR;
        return $Mid;
    }
    private function _produce_door_predict () {
        $Mid = 0;
        $Num = count($this->_Door);
        if ($Num > 0) {
            sort($this->_Door);
            if ($Num >= THREE) {
                array_pop($this->_Door);
                array_shift($this->_Door);
                $Mid = array_sum($this->_Door) / ($Num - TWO);
            } else {
                $Mid = array_sum($this->_Door) / $Num;
            }
        }
        $Mid = round(($Num/THIRTY)*$this->_Search['days'] * $Mid *M_REGULAR)/M_REGULAR;
        return $Mid;
    }
    private function _produce_wood_predict () {
        $Mid = 0;
        $Num = count($this->_Wood);
        if ($Num > 0) {
            sort($this->_Wood);
            if ($Num >= THREE) {
                array_pop($this->_Wood);
                array_shift($this->_Wood);
                $Mid = array_sum($this->_Wood) / ($Num - TWO);
            } else {
                $Mid = array_sum($this->_Wood) / $Num;
            }
        }
        $Mid = round(($Num/THIRTY)*$this->_Search['days'] * $Mid *M_REGULAR)/M_REGULAR;
        return $Mid;
    }
    private function _produce_fitting_predict () {
        $Mid = 0;
        $Num = count($this->_Fitting);
        if ($Num > 0) {
            sort($this->_Fitting);
            if ($Num >= THREE) {
                array_pop($this->_Fitting);
                array_shift($this->_Fitting);
                $Mid = array_sum($this->_Fitting) / ($Num - TWO);
            } else {
                $Mid = array_sum($this->_Fitting) / $Num;
            }
        }
        $Mid = round(($Num/THIRTY)*$this->_Search['days'] * $Mid *M_REGULAR)/M_REGULAR;
        return $Mid;
    }
    private function _produce_other_predict () {
        $Mid = 0;
        $Num = count($this->_Other);
        if ($Num > 0) {
            sort($this->_Other);
            if ($Num >= THREE) {
                array_pop($this->_Other);
                array_shift($this->_Other);
                $Mid = array_sum($this->_Other) / ($Num - TWO);
            } else {
                $Mid = array_sum($this->_Other) / $Num;
            }
        }
        $Mid = round(($Num/THIRTY)*$this->_Search['days'] * $Mid *M_REGULAR)/M_REGULAR;
        return $Mid;
    }
    private function _produce_server_predict () {
        $Mid = 0;
        $Num = count($this->_Server);
        if ($Num > 0) {
            sort($this->_Server);
            if ($Num >= THREE) {
                array_pop($this->_Server);
                array_shift($this->_Server);
                $Mid = array_sum($this->_Server) / ($Num - TWO);
            } else {
                $Mid = array_sum($this->_Server) / $Num;
            }
        }
        $Mid = round(($Num/THIRTY)*$this->_Search['days'] * $Mid *M_REGULAR)/M_REGULAR;
        return $Mid;
    }
    private function _produce_sum_predict () {
        $Mid = 0;
        $Num = count($this->_Sum);
        if ($Num > 0) {
            sort($this->_Sum);
            if ($Num >= THREE) {
                array_pop($this->_Sum);
                array_shift($this->_Sum);
                $Mid = array_sum($this->_Sum) / ($Num - TWO);
            } else {
                $Mid = array_sum($this->_Sum) / $Num;
            }
        }
        $Mid = round(($Num/THIRTY)*$this->_Search['days'] * $Mid * M_REGULAR)/M_REGULAR;
        return $Mid;
    }
}