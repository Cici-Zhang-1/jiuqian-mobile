<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月19日
 * @author Zhangcc
 * @version
 * @des
 */
class Income_pay extends MY_Controller{
    private $_Module = 'finance';
    private $_Controller;
    private $_Item ;
    public function __construct(){
        parent::__construct();
        $this->load->model('finance/income_pay_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';

        log_message('debug', 'Controller Finance/Income_pay Start!');
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

    public function read($Type = 'all'){
        $Type = trim($Type);
        $Data = array();
        if('all' == $Type){
            $Type = array('income', 'pay');
        }elseif ('income' == $Type){
            $Type = array('income');
        }elseif ('pay' == $Type){
            $Type = array('pay');
        }else{
            $Type = array('income', 'pay');
        }
        if(!!($Data = $this->income_pay_model->select($Type))){
            $Content = $Data['content'];
            foreach ($Content as $key => $value){
                if('income' == $value['type']){
                    $Content[$key]['type_cn'] = '收入';
                }else{
                    $Content[$key]['type_cn'] = '支出';
                }
            }
            $Data['content'] = $Content;
            unset($Content);
            $this->Success = '获取收支类型成功!';
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的收支类型';
        }
        $this->_return($Data);
    }

    public function add(){
        $Run = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $Post = gh_escape($_POST);
            if(!!($Id = $this->income_pay_model->insert($Post))){
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
            if(!!($Return = $this->income_pay_model->update($Post, $Where))){
                $this->Success .= '进账修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'进账修改失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
