<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月5日
 * @author Zhangcc
 * @version
 * @des
 * 权限列表
 */
class Priviledge_model extends MY_Model{
    private $_Module;
    private $_Model;
    private $_Item;
    private $_Cache;
    private $_Num;

    public function __construct(){
        parent::__construct(false);
        $this->_Module = str_replace("\\", "/", dirname(__FILE__));
        $this->_Module = substr($this->_Module, strrpos($this->_Module, '/')+1);
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = str_replace('/', '_', $this->_Item);
        
        log_message('debug', 'Model Manage/Priviledge_model Start');
    }

    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('priviledge', $Data)){
            log_message('debug', "Model Priviledge_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Priviledge_model/insert Error");
            return false;
        }
    }
}
