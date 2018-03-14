<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月5日
 * @author Zhangcc
 * @version
 * @des
 */
class Operation_model extends MY_Model{
    private $_Module;
    private $_Model;
    private $_Item;
    private $_Cache;

    public function __construct(){
        log_message('debug', 'Model Manage/Operation_model Start!');
        parent::__construct();

        $this->_Module = str_replace("\\", "/", dirname(__FILE__));
        $this->_Module = substr($this->_Module, strrpos($this->_Module, '/')+1);
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = str_replace('/', '_', $this->_Item);
    }

    public function select(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('operation')->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    public function select_operation_id($Name){
        $Query = $this->HostDb->select('o_id')->from('operation')->where('o_name', $Name)->limit(1)->get();
        if($Query->num_rows()  > 0){
            $Row = $Query->row_array();
            return $Row['o_id'];
        }else{
            return false;
        }
    }

    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('operation', $Data)){
            log_message('debug', "Model Operation_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Operation_model/insert Error");
            return false;
        }
    }

    public function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $this->HostDb->where('o_id', $Where);
        $this->HostDb->update('operation', $Data);
        $this->remove_cache($this->_Cache);
        return TRUE;
    }
}
