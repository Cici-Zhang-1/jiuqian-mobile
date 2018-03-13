<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月22日
 * @author Zhangcc
 * @version
 * @des
 * 支付
 */
class Finance_pay extends MY_Controller{
    private $_Module = 'finance';
    private $_Controller;
    private $_Item ;
    private $_Cookie;
    private $Search = array(
        'start_date' => '',
        'end_date' => '',
        'type' => '',
        'account' => '',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->load->model('finance/finance_pay_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';

        log_message('debug', 'Controller Finance/Finance_pay Start!');
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
            if(!!($Data = $this->finance_pay_model->select($this->Search))){
                $this->Search['pn'] = $Data['pn'];
                $this->Search['num'] = $Data['num'];
                $this->Search['p'] = $Data['p'];
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($this->Search), 'expire' => HOURS));
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的支出';
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
            if(!!($Id = $this->finance_pay_model->insert($Post))){
                $Data = array(
                    'out' => $Post['amount'],
                    'out_fee' => $Post['fee']
                );
                $this->load->model('finance/account_model');
                $this->account_model->update_balance_out($Data, $Post['faid']);
                
                if('内部转账' == $Post['type'] && !empty($Post['in_faid'])){
                    $Data = array(
                        'in' => $Post['amount'] - $Post['fee'],
                        'in_fee' => 0
                    );
                    $this->account_model->update_balance_in($Data, $Post['in_faid']);
                }
                $this->Success .= '支出新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'支出新增失败';
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
            if(!!($Return = $this->finance_pay_model->update($Post, $Where))){
                $this->load->model('finance/account_model');
                if(isset($Return['out'])){
                    foreach ($Return['out'] as $key => $value){
                        $Data = array(
                            'out' => $value['amount'],
                            'out_fee' => $value['fee']
                        );
                        $this->account_model->update_balance_out($Data, $key);
                    }
                }
                if(isset($Return['in'])){
                    foreach ($Return['in'] as $key => $value){
                        $Data = array(
                            'in' => $value['amount'],
                            'in_fee' => $value['fee']
                        );
                        $this->account_model->update_balance_in($Data, $key);
                    }
                }
                if('内部转账' == $Post['type'] && !empty($Post['in_faid'])){
                    $Data = array(
                        'in' => $Post['amount'] - $Post['fee'],
                        'in_fee' => 0
                    );
                    $this->account_model->update_balance_in($Data, $Post['in_faid']);
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
            if(!!($FinancePay = $this->finance_pay_model->delete($Where))){
                unset($Where);
                $this->load->model('finance/account_model');
                if(isset($FinancePay['out'])){
                    foreach ($FinancePay['out'] as $key => $value){
                        $Data = array(
                            'out' => $value['amount'],
                            'out_fee' => $value['fee']
                        );
                        $this->account_model->update_balance_out($Data, $key);
                    }
                }
                if(isset($FinancePay['in'])){
                    foreach ($FinancePay['in'] as $key => $value){
                        $Data = array(
                            'in' => $value['amount'],
                            'in_fee' => $value['fee']
                        );
                        $this->account_model->update_balance_in($Data, $key);
                    }
                }
                
                $this->Success .= '支出删除改成功, 刷新后生效!';
            }else{
                $this->Failue = '支出不存在, 不可删除!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
