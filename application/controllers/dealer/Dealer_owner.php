<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月20日
 * @author Administrator
 * @version
 * @des
 */
class Dealer_owner extends MY_Controller{
    private $_Module;
    private $_Controller;
    private $_Item;

    public function __construct(){
        parent::__construct();
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        
        $this->load->model('dealer/dealer_owner_model');
        log_message('debug', 'Controller Dealer/Dealer_claim Start!');
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
    public function _read(){
        $Id = $this->input->get('id');
        $Id = intval(trim($Id));
        if($Id > 0 && !!($Data = $this->dealer_owner_model->select_owner($Id))){
            $this->Success = '获取所有客户属主成功!';
        }else{
            $Data['Error'] = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'获取所有客户属主失败!';
        }
        $Data['Id'] = $Id;
        $this->load->view('dealer/dealer_owner/_read', $Data);
    }
    
    public function read(){
        if(!!($Data = $this->dealer_owner_model->select())){
            $Content = array();
            foreach ($Data['content'] as $key => $value){
                $Content[$key] = $value;
                if(empty($value['checker'])){
                    $Content[$key]['checker'] = $value['dealer_linker'];
                }
                if(empty($value['payer'])){
                    $Content[$key]['payer'] = $value['dealer_linker'];
                }
                if(empty($value['checker_phone'])){
                    $Content[$key]['checker_phone'] = $value['dealer_phone'];
                }
                if(empty($value['payer_phone'])){
                    $Content[$key]['payer_phone'] = $value['dealer_phone'];
                }
            }
            $Data['content'] = $Content;
            $this->Success = '获取所有供应商信息成功!';
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有符合条件的供应商';
        }
        $this->_return($Data);
    }
    
    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if(!!($this->form_validation->run($Item))){
            $Post = gh_escape($_POST);
            if(!is_array($Post['uid'])){
                $Uid = implode(',', $Post);
            }else{
                $Uid = $Post['uid'];
            }
            $Set = array();
            foreach ($Uid as $key => $value){
                $Set[] = array(
                    'uid' => $value,
                    'did' => $Post['dealer_id'],
                    'primary' => $Post['primary']
                );
            }
            unset($Post['uid']);
            if(!!($this->dealer_owner_model->replace_batch($Set))){
                $this->Success .= '客户属主新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'客户属主新增失败';
            }
        }else{
            $this->Failue = validation_errors();
        }
        $this->_return();
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
        $Owner = $this->input->post('user', true);
        $Selected = $this->input->post('selected', true);
        $Selected = explode(',', $Selected);
        foreach ($Selected as $key => $value){
            $Selected[$key] = intval(trim($value));
            if($Selected[$key] <= 0){
                unset($Selected[$key]);
            }
        }
        if(count($Selected) > 0){
            $this->dealer_owner_model->delete_by_did($Selected);
            foreach ($Selected as $key => $value){
                foreach ($Owner as $ikey => $ivalue){
                    $Set[] = array(
                        'uid' => $ivalue,
                        'did' => $value
                    );
                }
            }
            if(!!($this->dealer_owner_model->insert_batch($Set))){
                $this->Success = '认领成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'认领失败';
            }
        }else{
            $this->Failue = '没有可认领的经销商!';
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
    
    public function primary(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($this->dealer_owner_model->primary($Where)){
                $this->Success .= '客户首要属主设置成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'客户首要属主设置失败!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    public function general(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($this->dealer_owner_model->general($Where)){
                $this->Success .= '客户普通属主设置成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'客户普通属主设置失败!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    
    /**
     * 删除属主
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($this->dealer_owner_model->delete($Where)){
                $this->Success .= '客户属主删除成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'经销商属主删除失败!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
