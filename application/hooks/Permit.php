<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月5日
 * @author Zhangcc
 * @version
 * @des
 * 用户权限判断
 */
 
class Permit{
    /**
     * CI句柄
     */
    private $_CI;
    
    private $_Operation;
    
    public function __construct(){
        $this->_CI = & get_instance();
        log_message('debug', "Hook Permit/__construct Start");
    }
    
    /**
     * 判断用户是否可以执行当前操作
     */
    public function is_permit(){
        $this->_CI->load->model('manage/usergroup_priviledge_model');
        $Ugid = $this->_CI->session->userdata('ugid');
        $Ugid = intval(trim($Ugid));
        $Return = false;
        if(!$this->_allowed_page() && !$this->_is_test()){
            if($this->_get_operation()){
                if(!!($UsergroupPriviledge = $this->_CI->usergroup_priviledge_model->select_operation($Ugid))){
                    foreach ($UsergroupPriviledge as $value){
                        if($this->_Operation == $value['url']){
                            $Return = TRUE;
                            break;
                        }
                    }
                }
            }
        }else{
            $Return = TRUE;
        }
        if(!$Return){
            show_error('不好意思, 您无权执行此操作!');
        }
    }
    
    /**
     * 设置当前操作
     */
    private function _get_operation(){
        $this->_Operation = $this->_CI->router->directory.$this->_CI->router->class.'/'.$this->_CI->router->method;
        $Uri = explode('/', trim(str_replace($this->_Operation, '', uri_string()), '/'));
        if('index' == $this->_CI->router->method){
            if(count($Uri) > 0){
                $this->_Operation = $this->_Operation.'/'.array_shift($Uri);
            }else{
                return false;
            }
        }
        return true;
    }
    
    /**
     * 判断是否是执行登录/登出操作
     */
    private function _allowed_page(){
        $Uri = uri_string();
        return preg_match('/^(sign\/|home\/)/', uri_string())||empty($Uri);
    }
    
    /**
     * 判断是否未测试
     */
    private function _is_test(){
        return true;
    }
}
