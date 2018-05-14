<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月19日
 * @author Administrator
 * @version
 * @des
 * 每日确认
 */
class Everyday_asured extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;
    private $_Cookie;

    private $Count;
    private $Insert;
    private $Search = array(
        'year' => '',
        'month' => '',
        'day' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        log_message('debug', 'Controller Everyday_asure/Everyday_asure __construct Start!');
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
        $this->Search['day'] = $this->input->get('day');
        if(empty($this->Search['year'])){
            $this->Search['year'] = date('Y');
        }
        if(empty($this->Search['month'])){
            $this->Search['month'] = date('m');
        }
        if(empty($this->Search['day'])){
            $this->Search['day'] = date('d');
        }

        $this->Search['start_date'] = date('Y-m-d H:i:s', mktime(0,0,0,$this->Search['month'],$this->Search['day'],$this->Search['year']));
        $this->Search['end_date'] = date('Y-m-d H:i:s', mktime(0,0,0,$this->Search['month'],$this->Search['day']+1,$this->Search['year']));
        $Data = array();
        if(!!($Return = $this->order_model->select_everyday_asured($this->Search))){
            $Sum = 0;
            $Num = 0;
            foreach ($Return as $key => $value){
                $Sum += $value['sum'];
            }
            $Num = count($Return);
            $Return[] = array(
                'order_num' => '共'.$Num.'单',
                'dealer' => '',
                'owner' => '总额',
                'creator' => '',
                'create_datetime' => '',
                'asure_datetime' => '',
                'sum' => $Sum
            );
            unset($Sum);
            $Data['content'] = $Return;
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }
}
