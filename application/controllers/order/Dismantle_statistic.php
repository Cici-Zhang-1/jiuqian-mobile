<?php
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 2017/8/20
 * Time: 22:21
 *
 * Desc:统计自己的拆单数据统计数据
 */

class Dismantle_statistic extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $Count;
    private $Insert;
    private $Search = array(
        'start_date' => '',
        'end_date' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_product_board_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        log_message('debug', 'Controller Dismantle_statistic/Dismantle_statistic __construct Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $Data['Uid'] = $this->session->userdata('uid');
            $this->load->view($Item, $Data);
        }
    }

    public function read(){
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
        if(!!($Return = $this->order_product_board_model->select_dismantle_area($this->Search))){
            $Statictic = array(
                '橱柜' => array('name' => '橱柜', 'eighteen' => 0, 'twentyfive' => 0, 'thirtysix' => 0, 'five' => 0, 'nine' => 0),
                '衣柜' => array('name' => '衣柜', 'eighteen' => 0, 'twentyfive' => 0, 'thirtysix' => 0, 'five' => 0, 'nine' => 0),
                '门板' => array('name' => '门板', 'eighteen' => 0, 'twentyfive' => 0, 'thirtysix' => 0, 'five' => 0, 'nine' => 0),
                '木框门' => array('name' => '木框门', 'eighteen' => 0, 'twentyfive' => 0, 'thirtysix' => 0, 'five' => 0, 'nine' => 0),
                '总面积' => array('name' => '总面积', 'eighteen' => 0, 'twentyfive' => 0, 'thirtysix' => 0, 'five' => 0, 'nine' => 0),
                '厚板' => array('name' => '厚板', 'eighteen' => 0, 'twentyfive' => 0, 'thirtysix' => 0, 'five' => 0, 'nine' => 0)
            );
            $SizeName = array(
                5 => 'five',
                9 => 'nine',
                18 => 'eighteen',
                25 => 'twentyfive',
                36 => 'thirtysix'
            );

            $Sum = array(
                'five' => 0,
                'nine' => 0,
                'eighteen' => 0,
                'twentyfive' => 0,
                'thirtysix' => 0
            );

            foreach ($Return as $key => $value){
                if (isset($SizeName[$value['board']])) {
                    $Statictic[$value['name']][$SizeName[$value['board']]] = $value['area'];
                    $Sum[$SizeName[$value['board']]] += $value['area'];
                }
            }
            $Statictic['总面积'] = array_merge($Statictic['总面积'], $Sum);
            $Statictic['厚板']['eighteen'] = $Sum['eighteen'] + $Sum['twentyfive'] + $Sum['thirtysix'];
            $Data['content'] = $Statictic;
            unset($Statictic,$Return);
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
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
