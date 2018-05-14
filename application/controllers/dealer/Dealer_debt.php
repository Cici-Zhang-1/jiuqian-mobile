<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月22日
 * @author Zhangcc
 * @version
 * @des
 * 经销商对账
 */
class Dealer_debt extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;

    public function __construct(){
        parent::__construct();
        $this->load->model('dealer/dealer_model');
        $this->load->model('order/order_model');
        $this->load->model('finance/finance_received_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';

        log_message('debug', 'Controller Dealer/Dealer_debt Start!');
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

    private function _read(){
        $Id = $this->input->get('id', true);
        $Id = intval(trim($Id));
        if($Id > 0){
            $Data['Id'] = $Id;
            if(!!($Data['Info'] = $this->dealer_model->is_valid($Id))){
                $CurrentYear = date('Y');
                $CurrentMonth = date('m');
                $Year = $this->input->get('year', true);
                $Month = $this->input->get('month', true);
                $Year = intval(trim($Year));
                $Month = intval(trim($Month));
                if($Year > $CurrentYear){
                    $Year = $CurrentYear;
                }elseif ($Year < 2014){
                    $Year = $CurrentYear;
                }
                if(0 == $Month){
                    $Month = 1;
                    $StartDatetime = sprintf('%d-%02d-01 00:00:00', $Year, $Month);
                    $Data['Year'] = $Year;
                    $Data['Month'] = $Month;
                    if(12 == $CurrentMonth){
                        $Year++;
                        $Month = 1;
                    }elseif ($CurrentYear > $Year) {
                        $Year++;
                        $Month = 1;
                    }else{
                        $Month = $CurrentMonth + 1;
                    }
                    $EndDatetime = sprintf('%d-%02d-01 00:00:00', $Year, $Month);
                }else{
                    if(($Month > 12 || $Month < 0) || ($Year == $CurrentYear && $Month > $CurrentMonth)){
                        $Month = $CurrentMonth;
                    }
                    $StartDatetime = sprintf('%d-%02d-01 00:00:00', $Year, $Month);
                    $Data['Year'] = $Year;
                    $Data['Month'] = $Month;
                    if(12 == $Month){
                        $Year++;
                        $Month = 1;
                    }else{
                        $Month++;
                    }
                    $EndDatetime = sprintf('%d-%02d-01 00:00:00', $Year, $Month);
                }
                $Data['Order'] = $this->order_model->select_for_debt($Id, $StartDatetime, $EndDatetime);
                $Data['Received'] = $this->finance_received_model->select_for_debt($Id, $StartDatetime, $EndDatetime);
                $this->load->view('header2');
                $this->load->view($this->_Item.__FUNCTION__, $Data);
            }else{
                show_error('您选择的经销商不存在!');
            }
        }else{
            show_error('请先选择经销商, 再对账!');
        }
    }
}
