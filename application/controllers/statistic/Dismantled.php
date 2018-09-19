<?php
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 2017/8/20
 * Time: 22:21
 *
 * Desc:统计自己的拆单数据统计数据
 */

class Dismantled extends MY_Controller {
    private $__Search = array(
        'start_date' => '',
        'end_date' => '',
        'dismantle' => ZERO
    );
    private $_Predict = array(
        'cabinet' => 0,
        'wardrobe' => 0,
        'door' => 0,
        'wood' => 0,
        'area' => 0
    );
    private $_Eighteen = array(); // 18mm
    private $_TwentyFive = array(); // 25mm
    private $_ThirtySix = array(); // 36mm
    private $_Five = array(); // 5mm
    private $_Nine = array(); // 9mm
    private $_Other = array();
    private $_Thick = 0; // 厚板总面积
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Statistic/Dismantled __construct Start!');
        $this->load->model('order/order_product_board_model');
        $this->_Eighteen = $this->_TwentyFive = $this->_ThirtySix = $this->_Five = $this->_Nine = $this->_Other = $this->_Predict;
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if (empty($this->_Search['start_date']) && empty($this->_Search['end_date'])) {
            $this->_Search['start_date'] = date('Y-m-01');
            $this->_Search['end_date'] = date('Y-m-d', strtotime('+1 day'));
        } elseif (empty($this->_Search['start_date']) && !empty($this->_Search['end_date'])) {
            $this->_Search['start_date'] = date('Y-m-01', gh_to_sec($this->_Search['end_date']));
        }
        if ($this->_Search['start_date'] >= $this->_Search['end_date']) {
            $this->_Search['start_date'] = date('Y-m-01', gh_to_sec($this->_Search['end_date']));
        }
        if (empty($this->_Search['dismantle'])) {
            $this->_Search['dismantle'] = $this->session->userdata('uid');
        }

        $Data = array();
        if(!!($Query = $this->order_product_board_model->select_dismantled($this->_Search))){
            foreach ($Query as $Key => $Value) {
                switch ($Value['thick']) {
                    case 5:
                        $this->_five($Value);
                        break;
                    case 9:
                        $this->_nine($Value);
                        break;
                    case 18:
                        $this->_eighteen($Value);
                        break;
                    case 25:
                        $this->_twenty_five($Value);
                        break;
                    case 36:
                        $this->_thirty_six($Value);
                        break;
                    default:
                        $this->_other($Value);
                }
                if ($Value['thick'] > THICK) {
                    $this->_Thick += $Value['area'];
                }
            }
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
                    'eighteen' => $this->_Eighteen[$key],
                    'twenty_five' => $this->_TwentyFive[$key],
                    'thirty_six' => $this->_ThirtySix[$key],
                    'five' => $this->_Five[$key],
                    'nine' => $this->_Nine[$key],
                    'other' => $this->_Other[$key]
                );
            }
            array_push($Tmp, array(
                'name' => '厚板[平方]',
                'eighteen' => $this->_Thick,
                'twenty_five' => 0,
                'thirty_six' => 0,
                'five' => 0,
                'nine' => 0,
                'other' => 0
            ));
            $Data['content'] = $Tmp;
            $Data['num'] = count($Tmp);
            $Data['p'] = ONE;
            $Data['pn'] = ONE;
            $Data['pagesize'] = ALL_PAGESIZE;
        }else{
            $this->Message = '对不起, 没有符合条件的内容!';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
    private function _five ($Board) {
        switch ($Board['product_id']) {
            case CABINET:
                $this->_Five['cabinet'] += $Board['area'];
                break;
            case WARDROBE:
                $this->_Five['wardrobe'] += $Board['area'];
                break;
            case DOOR:
                $this->_Five['door'] += $Board['area'];
                break;
            case WOOD:
                $this->_Five['wood'] += $Board['area'];
                break;
        }
        $this->_Five['area'] += $Board['area'];
    }
    private function _nine ($Board) {
        switch ($Board['product_id']) {
            case CABINET:
                $this->_Nine['cabinet'] += $Board['area'];
                break;
            case WARDROBE:
                $this->_Nine['wardrobe'] += $Board['area'];
                break;
            case DOOR:
                $this->_Nine['door'] += $Board['area'];
                break;
            case WOOD:
                $this->_Nine['wood'] += $Board['area'];
                break;
        }
        $this->_Nine['area'] += $Board['area'];
    }
    private function _eighteen ($Board) {
        switch ($Board['product_id']) {
            case CABINET:
                $this->_Eighteen['cabinet'] += $Board['area'];
                break;
            case WARDROBE:
                $this->_Eighteen['wardrobe'] += $Board['area'];
                break;
            case DOOR:
                $this->_Eighteen['door'] += $Board['area'];
                break;
            case WOOD:
                $this->_Eighteen['wood'] += $Board['area'];
                break;
        }
        $this->_Eighteen['area'] += $Board['area'];
    }
    private function _twenty_five ($Board) {
        switch ($Board['product_id']) {
            case CABINET:
                $this->_TwentyFive['cabinet'] += $Board['area'];
                break;
            case WARDROBE:
                $this->_TwentyFive['wardrobe'] += $Board['area'];
                break;
            case DOOR:
                $this->_TwentyFive['door'] += $Board['area'];
                break;
            case WOOD:
                $this->_TwentyFive['wood'] += $Board['area'];
                break;
        }
        $this->_TwentyFive['area'] += $Board['area'];
    }
    private function _thirty_six ($Board) {
        switch ($Board['product_id']) {
            case CABINET:
                $this->_ThirtySix['cabinet'] += $Board['area'];
                break;
            case WARDROBE:
                $this->_ThirtySix['wardrobe'] += $Board['area'];
                break;
            case DOOR:
                $this->_ThirtySix['door'] += $Board['area'];
                break;
            case WOOD:
                $this->_ThirtySix['wood'] += $Board['area'];
                break;
        }
        $this->_ThirtySix['area'] += $Board['area'];
    }
    private function _other ($Board) {
        switch ($Board['product_id']) {
            case CABINET:
                $this->_Other['cabinet'] += $Board['area'];
                break;
            case WARDROBE:
                $this->_Other['wardrobe'] += $Board['area'];
                break;
            case DOOR:
                $this->_Other['door'] += $Board['area'];
                break;
            case WOOD:
                $this->_Other['wood'] += $Board['area'];
                break;
        }
        $this->_Other['area'] += $Board['area'];
    }

    private function _detail() {
        $this->Search['start_date'] = $this->input->get('start_date');
        $this->Search['end_date'] = $this->input->get('end_date');
        if(empty($this->Search['start_date']) && empty($this->Search['end_date'])){ //没有选择日期
            $this->Search['start_date'] = date('Y-m-d H:i:s', mktime(0,0,0,date('m'),1,date('Y')));
            $this->Search['end_date'] = date('Y-m-d H:i:s', mktime(0,0,0,date('m') + 1, 1, date('Y')));
        }elseif (!empty($this->Search['start_date']) && empty($this->Search['end_date'])){  //没有截止日期
            $this->Search['end_date'] = date('Y-m-d H:i:s', mktime(0,0,0,date('m') + 1, 1, date('Y')));
        }elseif (empty($this->Search['year']) && !empty($this->Search['month'])){   //没有开始日期
            $this->Search['start_date'] = date('Y-m-d H:i:s', mktime(0,0,0,1,1,1970));
        }

        $this->Search['self'] = $this->input->get('uid');
        if (empty($this->Search['self'])) {
            $this->Search['self'] = $this->session->userdata('uid');
        }
        $Data = array();
        if(!!($Return = $this->order_product_board_model->select_dismantle_area_detail($this->Search))){
            $Data['detail'] = $Return;
            $this->load->view('header2');
            $this->load->view($this->_Item.__FUNCTION__, $Data);
        }else {
            $this->Failue = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有您需要的明细!';
            show_error($this->Failue);
        }

    }
}