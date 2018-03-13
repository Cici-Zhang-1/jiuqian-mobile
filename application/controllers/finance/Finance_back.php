<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月23日
 * @author Zhangcc
 * @version
 * @des
 * 返款
 */
class Finance_back extends MY_Controller{
    private $_Module = 'finance';
    private $_Controller;
    private $_Item ;
    private $_Cookie;
    private $Search = array(
        'status' => '2',
        'type' => '返款',
        'account' => '',
        'start_date' => '',
        'end_date' => '',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';

        log_message('debug', 'Controller Finance/Finance_back Start!');
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
            $this->load->model('finance/finance_received_model');
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

    public function edit(){
        $Run = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $Selected = $this->input->post('selected');
            $Selected = explode(',', $Selected);
            $Faid = $this->input->post('faid');
            $Remark = $this->input->post('remark');
            
            $this->load->model('finance/finance_received_model');
            if(!!($Return = $this->finance_received_model->update_back($Selected, $this->Search['status']))){
                $Data = array(
                    'out' => 0,
                    'out_fee' => 0
                );
                $Set = array();
                foreach ($Return as $key => $value){
                    $Data['out'] += $value['amount'];/*出账费用，为进账费用+手续费*/
                    $Data['out_fee'] += $value['fee'];
                    
                    $Set[] = array(
                        'faid' => $Faid,
                        'type' => '返款',
                        'amount' => $value['amount'],
                        'fee' => $value['fee'],
                        'bank_date' => date('Y-m-d'),
                        'remark' => $value['dealer'].','.$value['remark']
                    );
                }
                unset($Return);
                $this->load->model('finance/account_model');
                $this->load->model('finance/finance_pay_model');
                /*更新财务金额*/
                $this->account_model->update_balance_out($Data, $Faid)
                 && $this->finance_pay_model->insert_batch($Set);
                $this->Success .= '返款成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'返款失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
