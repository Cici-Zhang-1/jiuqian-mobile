<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月9日
 * @author Zhangcc
 * @version
 * @des
 * 财务账户
 */
class Account extends MY_Controller{
    private $_Module = 'finance';
    private $_Controller;
    private $_Item;
    public function __construct(){
        parent::__construct();
        $this->load->model('finance/account_model');
        $this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
        
        log_message('debug', 'Controller Finance/Account eStart!');
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
        $Data = array();
        if(!($Query = $this->account_model->select_account())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有财务账户';
        }else{
            $Statistics = array(
                'faid' => '0',
                'name' => '统计',
                'host' => '',
                'account' => '',
                'balance' => 0,
                'in' => 0,
                'in_fee' => 0,
                'out' => 0,
                'out_fee' => 0,
                'fee' => 0,
                'fee_max' => 0,
                'intime' => 0
            );
            foreach ($Query as $key => $value){
                $Statistics['balance'] += $value['balance'];
                $Statistics['in'] += $value['in'];
                $Statistics['in_fee'] += $value['in_fee'];
                $Statistics['out'] += $value['out'];
                $Statistics['out_fee'] += $value['out_fee'];
            }
            $Query[] = $Statistics;
            $Data['content'] = $Query;
            unset($Statistics, $Query);
        }
        $this->_return($Data);
    }
    /**
     * 读取财务账户名称
     */
    public function read_name(){
        $Data = array();
        if(!($Query = $this->account_model->select_account_name())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有财务账户';
        }else{
            $Data['content'] = $Query;
        }
        $this->_return($Data);
    }
    
    public function read_intime(){
        $Data = array();
        if(!($Query = $this->account_model->select_intime())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有财务账户';
        }else{
            $Data['content'] = $Query;
        }
        $this->_return($Data);
    }
    
    public function read_outtime(){
        $Data = array();
        if(!($Query = $this->account_model->select_outtime())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有财务账户';
        }else{
            $Data['content'] = $Query;
        }
        $this->_return($Data);
    }
    
    public function add(){
        $Run = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Run)){
            $Post = gh_escape($_POST);
	    $Post['balance'] = $Post['in'] - $Post['out'];
            if(!!($Id = $this->account_model->insert_account($Post))){
                $this->Success .= '账户新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'账户新增失败';
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
            $Post['balance'] = $Post['in'] - $Post['out'];
            if(!!($this->account_model->update_account($Post, $Where))){
                $this->Success .= '账户修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'账户修改失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    /**
     * 删除
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                if($this->account_model->delete_account($Where)){
                    $this->Success .= '账户删除成功, 刷新后生效!';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'账户删除失败';
                }
            }else{
                $this->Failue .= '没有可删除项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
