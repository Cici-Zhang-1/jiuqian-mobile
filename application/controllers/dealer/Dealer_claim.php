<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月19日
 * @author Zhangcc
 * @version
 * @des
 * 经销商认领
 */
class Dealer_claim extends CWDMS_Controller{
    private $_Module;
	private $_Controller;
	private $_Item;

    public function __construct(){
        parent::__construct();
        $this->load->model('dealer/dealer_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        
        log_message('debug', 'Controller Dealer/Dealer_claim Start!');
    }

    public function edit($Type){
        $Type = trim($Type);
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            switch ($Type){
                case 'in':
                    $this->_in();
                    break;
                case 'out':
                    $this->_out();
                    break;
                case 'other':
                    $this->_other();
                    break;
            }
        }else{
            $this->Failue = validation_errors();
        }
        $this->_return();
    }
    
    private function _in(){
        $Where = $this->input->post('selected', true);
        if(!!($this->dealer_model->update_owner($Where, $this->session->userdata('uid')))){
            $this->Success .= '认领成功, 刷新后生效!';
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'认领失败';
        }
    }
    
    private function _out(){
        $Where = $this->input->post('selected', true);
        if(!!($this->dealer_model->update_owner($Where))){
            $this->Success = '退领成功, 刷新后生效!';
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'退领失败&nbsp;&nbsp;';
        }
    }
    
    private function _other(){
        $Owner = $this->input->post('owner', true);
        $Where = $this->input->post('selected', true);
        $Where = explode(',', $Where);
        if(!!($this->dealer_model->update_owner($Where, $Owner))){
            $this->Success .= '分配成功, 刷新后生效!';
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'分配失败';
        }
    }
}