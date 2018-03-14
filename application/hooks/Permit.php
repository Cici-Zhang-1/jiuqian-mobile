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
    private $_Ugid;
    
    public function __construct(){
        log_message('debug', "Hook Permit/__construct Start");
        $this->_CI = & get_instance();
        $this->_Ugid = $this->_CI->session->userdata('ugid');
    }
    
    /**
     * 判断用户是否可以执行当前操作
     */
    public function is_permit(){
        $Return = false;
        if (ENVIRONMENT === 'development') {
            if(!$this->_is_public()){
                if($this->_get_operation()){
                    if (!!($this->_not_exist()) || !!($this->_is_allowed('menu')) || !!($this->_is_allowed('func')) || !!($this->_is_allowed('visit'))) {
                        $Return = true;
                    }else {
                        $Return = false;
                    }
                }else {
                    $Return = false;
                }
            }else{
                $Return = true;
            }
        }else {
            $Return = true;
        }

        if(!$Return){
            gh_return(EXIT_PERMISSION, 'Sorry, you visit non-exist page or you can not visit this page!');
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
            }else {
                $this->_Operation = $this->_Operation . '/' . '_read';
            }
        }
        $this->_Operation = '/' . $this->_Operation;
        $GLOBALS['Permission']['Operation'] = $this->_Operation;
        return true;
    }

    /**
     * 是否是公共许可访问的
     */
    private function _is_public(){
        return preg_match('/^(sign\/|home\/)/', uri_string())||empty(uri_string());
    }

    /**
     * 是否允许...
     * @param $Type
     * @return bool
     */
    private function _is_allowed($Type) {
        $Model = $Type . '_model';
        $this->_CI->load->model('permission/' . $Model);

        if (!!($Query = $this->_CI->$Model->select_is_allowed_operation($this->_Ugid, $this->_Operation))) {
            foreach ($Query as $Key => $Value) {
                $Key = name_to_id($Key, true);
                if (!isset($GLOBALS['Permission'][$Key])) {
                    $GLOBALS['Permission'][$Key] = $Value;
                }
            }
            log_message('debug', 'Permit allowed by ' . $Type . ' path '. $this->_Operation);
            return true;
        }
        log_message('debug', 'No permit allowed by ' . $Type . ' path'. $this->_Operation);
        return false;
    }

    private function _not_exist() {
        $this->_CI->load->model('permission/menu_model');
        $this->_CI->load->model('permission/func_model');
        $this->_CI->load->model('permission/visit_model');
        return $this->_CI->menu_model->select_not_exist_operation($this->_Operation) &&
            $this->_CI->func_model->select_not_exist_operation($this->_Operation) &&
            $this->_CI->visit_model->select_not_exist_operation($this->_Operation);
    }
}
