<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月25日
 * @author Zhangcc
 * @version
 * @des
 * 销售预计
 */
class Order_predict extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $Count;
    private $Insert;
    private $Search = array(
        'year' => '',
        'month' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        log_message('debug', 'Controller Order_predict/Order_predict __construct Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view($Item, $Data);
        }
    }

    public function read(){
        $this->Search['year'] = $this->input->get('year');
        $this->Search['month'] = $this->input->get('month');
        if(empty($this->Search['year']) && empty($this->Search['month'])){
            $this->Search['year'] = date('Y');
            $this->Search['month'] = date('m');
            $CurrentDay = date('d');
            $Days = cal_days_in_month(CAL_GREGORIAN, $this->Search['month'], $this->Search['year']);
        }elseif (!empty($this->Search['year']) && empty($this->Search['month'])){
            $this->Search['month'] = date('m');
            if($this->Search['year'] == date('Y')){
                $CurrentDay = date('d');
                $Days = cal_days_in_month(CAL_GREGORIAN, $this->Search['month'], $this->Search['year']);
            }else{
                $Days = cal_days_in_month(CAL_GREGORIAN, $this->Search['month'], $this->Search['year']);
                $CurrentDay = $Days;
            }
        }elseif (empty($this->Search['year']) && !empty($this->Search['month'])){
            $this->Search['year'] = date('Y');
            if($this->Search['month'] == date('m')){
                $CurrentDay = date('d');
                $Days = cal_days_in_month(CAL_GREGORIAN, $this->Search['month'], $this->Search['year']);
            }else{
                $Days = cal_days_in_month(CAL_GREGORIAN, $this->Search['month'], $this->Search['year']);
                $CurrentDay = $Days;
            }
        }else{
            if($this->Search['year'] == date('Y') && $this->Search['month'] == date('m')){
                $CurrentDay = date('d');
                $Days = cal_days_in_month(CAL_GREGORIAN, $this->Search['month'], $this->Search['year']);
            }else{
                $Days = cal_days_in_month(CAL_GREGORIAN, $this->Search['month'], $this->Search['year']);
                $CurrentDay = $Days;
            }
        }
        
        $this->Search['start_date'] = date('Y-m-d', mktime(0,0,0,$this->Search['month'],1,$this->Search['year']));
        $this->Search['end_date'] = date('Y-m-d', mktime(0,0,0,$this->Search['month'],$Days+1,$this->Search['year']));
        $Data = array();
        if(!!($Return = $this->order_model->select_order_predict($this->Search))
            && !!($Asured = $this->order_model->select_order_asured($this->Search))){
            /*分为两类，已经报价之后> 8和正在生产之后>=11*/
            $Predict1 = array(
                'cabinet' => 0,
                'wardrobe' => 0,
                'door' => 0,
                'kuang' => 0,
                'fitting' => 0,
                'other' => 0,
                'server' => 0,
                'sum' => 0
            );
            $Predict2 = $Predict1;
            $Predict3 = $Predict1;
            $Predict4 = $Predict1;
            $Predict0 = array(
                'cabinet' => '橱柜[￥]',
                'wardrobe' => '衣柜[￥]',
                'door' => '门板[￥]',
                'kuang' => '木框门[￥]',
                'fitting' => '配件[￥]',
                'other' => '外购[￥]',
                'server' => '服务[￥]',
                'sum' => '总额[￥]'
            );
            $this->load->helper('array');
            foreach ($Return as $key => $value){
                if($value['sum'] > 0 && !empty($value['sum_detail'])){
                    $SumDetail = json_decode($value['sum_detail'], true);
                    $SumDetail['sum'] = $value['sum'];
                    $Predict1 = element_sum($Predict1, $SumDetail);
                }
            }
            unset($Return);
            foreach ($Asured as $key => $value){
                if($value['sum'] > 0 && !empty($value['sum_detail'])){
                    $SumDetail = json_decode($value['sum_detail'], true);
                    $SumDetail['sum'] = $value['sum'];
                    $Predict2 = element_sum($Predict2, $SumDetail);
                }
            }
            unset($Asured);
            foreach ($Predict1 as $key => $value){
                $Predict3[$key] = round(($value/$CurrentDay)*$Days*M_REGULAR)/M_REGULAR;
            }
            foreach ($Predict2 as $key => $value){
                $Predict4[$key] = round(($value/$CurrentDay)*$Days*M_REGULAR)/M_REGULAR;
            }
            foreach ($Predict0 as $key => $value){
                $Return[] = array(
                    'name' => $value,
                    'quote' => $Predict1[$key],
                    'quote_predict' => $Predict3[$key],
                    'asure' => $Predict2[$key],
                    'asure_predict' => $Predict4[$key]
                );
            }
            $Data['content'] = $Return;
            unset($Predict0,$Predict1,$Predict2,$Predict3,$Predict4,$Return);
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }
}
