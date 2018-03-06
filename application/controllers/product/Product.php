<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年10月26日
 * @author Zhangcc
 * @version
 * @des
 * 产品类型
 */
class Product extends CWDMS_Controller{
    private $_Module;
	private $_Controller;
	private $_Item;
	private $_Cookie;
	
    public function __construct(){
        parent::__construct();
        $this->load->model('product/product_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';
        
        log_message('debug', 'Controller Product/Product Start!');
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

    public function read($Delete = 'delete'){
        $Delete = trim($Delete);
        switch ($Delete){
            case 'delete':
                /**
                 * 可删除的产品类型
                 * @var unknown
                 */
                $Data = $this->_read_delete();
                break;
            case 'undelete':
                /**
                 * 不可删除的产品类型
                 * @var unknown
                 */
                $Data = $this->_read_undelete();
                break;
            default:
                /**
                 * 其它产品类型
                 * @var unknown
                 */
                $Data = $this->_read_other($Delete);
        }
        $this->_return($Data);
    }
    
    private function _read_delete(){
        $Data = array();
        if(!!($Data = $this->product_model->select())){
            $Content = array();
            $Pid = 9999;
            foreach ($Data['content'] as $key => $value){
                $Return[$value['pid']] = $value;
                $Child[$value['parent']][] = $value['pid'];
                if($value['parent'] < $Pid){
                    $Pid = $value['parent'];
                }
            }
            ksort($Child);
            $Child = gh_infinity_category($Child, $Pid);
            while(list($key, $value) = each($Child)){
                $Content[] = $Return[$value];
            }
            $Data['content'] = $Content;
        }else{
            $this->Failue .= '没有产品类型信息';
        }
        return $Data;
    }
    
    private function _read_undelete(){
        $Data = false;
        if(!($Data = $this->product_model->select_undelete())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您访问的产品分类不存在';
        }
        return $Data;
    }
    
    private function _read_other($Type){
        $Data = array();
        if(!($Data = $this->product_model->select($Type))){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您访问的产品分类不存在';
        }else{
            $Content = array();
            $Pid = 9999;
            foreach ($Data['content'] as $key => $value){
                $Return[$value['pid']] = $value;
                $Child[$value['parent']][] = $value['pid'];
                if($value['parent'] < $Pid){
                    $Pid = $value['parent'];
                }
            }
            ksort($Child);
            $Child = gh_infinity_category($Child, $Pid);
            while(list($key, $value) = each($Child)){
                $Content[] = $Return[$value];
            }
            $Data['content'] = $Content;
        }
        return $Data;
    }
    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($this->product_model->insert($Post))){
                $this->Success .= '产品类型新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'产品类型新增失败!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            $Where = $Post['selected'];
            unset($Post['selected']);
            if(!!($this->product_model->update($Post, $Where))){
                $this->Success .= '产品类型信息修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'产品类型信息修改失败!';
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
                if($this->product_model->delete($Where)){
                    $this->Success .= '产品类型删除成功, 刷新后生效!';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'产品类型删除失败';
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