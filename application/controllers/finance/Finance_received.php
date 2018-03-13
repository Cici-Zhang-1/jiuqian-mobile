<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月9日
 * @author Zhangcc
 * @version
 * @des
 * 财务进账
 */
class Finance_received extends MY_Controller{
    private $_Module = 'finance';
    private $_Controller;
    private $_Item ;
    private $_Cookie;
    private $Search = array(
        'status' => '',  /*状态*/
        'start_date' => '', /*开始日期*/
        'end_date' => '',   /*结束日期*/
        'type' => '',       /*类型货款收入，返款，其它收入*/
        'account' => '',    /*对应账户*/
        'keyword' => ''    /*关键字*/
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('finance/finance_received_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller Finance/Finance_received Start!');
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
        $Cookie = $this->_Cookie.__FUNCTION__;
        $this->Search = $this->get_page_conditions($Cookie, $this->Search);
        $Data = array();
        if(!empty($this->Search)){
            if(!!($Data = $this->finance_received_model->select($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的订单';;
            }
        }else{
            $this->Failue = '对不起, 没有符合条件的内容!';
        }
        $this->_return($Data);
    }

    public function add(){
        $Run = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $Post = gh_escape($_POST);
            if(empty($Post['bank_date'])){
                $Post['bank_date'] = date('Y-m-d');
            }
            if(!!($Id = $this->finance_received_model->insert($Post))){
                $Data = array(
                    'in' => $Post['amount'],
                    'in_fee' => $Post['fee']
                );
                $this->load->model('finance/account_model');
                $this->account_model->update_balance_in($Data, $Post['faid']);
                $this->Success .= '进账新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'进账新增失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    public function edit(){
        $Run = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $Post = gh_escape($_POST);
            $Where = $this->input->post('selected');
            if(!!($Return = $this->finance_received_model->update($Post, $Where))){
                $this->load->model('finance/account_model');
                foreach ($Return as $key => $value){
                    $Data = array(
                        'in' => $value['amount'],
                        'in_fee' => $value['fee']
                    );
                    $this->account_model->update_balance_in($Data, $key);
                }
                $this->Success .= '进账修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'进账修改失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    /**
     * 废止
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if(!!($Query = $this->finance_received_model->delete_intime($Where))){
                unset($Where);
                $this->load->model('finance/account_model');
                $FinanceReceived = $Query['account'];
                foreach ($FinanceReceived as $key => $value){
                    $Data = array(
                        'in' => $value['amount'],
                        'in_fee' => $value['fee']
                    );
                    $this->account_model->update_balance_in($Data, $key);
                }
                $Dealer = $Query['dealer'];
                if(!empty($Dealer)){
                    $this->load->model('dealer/dealer_model');
                    foreach ($Dealer as $key => $value){
                        $this->dealer_model->update_dealer_received($value, $key);
                    }
                }
                $this->Success .= '进账删除改成功, 刷新后生效!';
            }else{
                $this->Failue = '登帐已经认领, 不可删除!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
