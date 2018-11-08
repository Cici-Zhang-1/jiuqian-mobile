<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月22日
 * @author Zhangcc
 * @version
 * @des
 * 经销商对账
 */
class Dealer_debt extends MY_Controller {
    private $__Search = array(
        'dealer_id' => ZERO,
        'start_date' => '',
        'end_date' => ''
    );

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Dealer/Dealer_debt Start!');
        $this->load->model('dealer/dealer_model');
        $this->load->model('order/order_model');
        $this->load->model('finance/finance_income_model');
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
        $Id = $this->input->get('dealer_id', true);
        $Id = intval(trim($Id));
        if($Id > 0){
            $Data['Id'] = $Id;
            if(!!($Data['Info'] = $this->dealer_model->is_exist($Id))){
                $StartDatetime = $this->input->get('start_date', true);
                if (empty($StartDatetime)) {
                    $StartDatetime = date('Y-m-01');
                }
                $EndDatetime = $this->input->get('end_date', true);
                if (empty($EndDatetime)) {
                    $EndDatetime = date('Y-m-01', strtotime('+1 month'));
                }
                $Data['Order'] = $this->order_model->select_for_debt($Id, $StartDatetime, $EndDatetime);
                $Data['Received'] = $this->finance_income_model->select_for_debt($Id, $StartDatetime, $EndDatetime);
                $Data['StartDate'] = $StartDatetime;
                $Data['EndDate'] = $EndDatetime;
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