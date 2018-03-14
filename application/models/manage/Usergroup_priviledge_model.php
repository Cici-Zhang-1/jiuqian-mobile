<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月5日
 * @author Administrator
 * @version
 * @des
 */
class Usergroup_priviledge_model extends MY_Model{
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
	    
	    log_message('debug', 'Model Manage/User_model Start');
    }

    /**
     * 获得用户可操作权限
     * @param unknown $Ugid
     */
    public function select_operation($Ugid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Ugid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('usergroup_priviledge')
                        ->join('priviledge', 'p_id = up_priviledge_id', 'left')
                        ->join('operation', 'o_id = p_source_id', 'left')
                        ->where('up_usergroup_id', $Ugid)
                        ->where('p_type', 'operation')
                        ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    
    /**
     * 获得菜单导航
     * @param unknown $Ugid
     */
    public function select_apps($Ugid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$Ugid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)
                                    ->from('usergroup_priviledge')
                                    ->join('priviledge', 'p_id = up_priviledge_id', 'left')
                                    ->join('menu', 'm_id = p_source_id', 'left')
                                    ->where('up_usergroup_id', $Ugid)
                                    ->where('p_type', 'menu')
                                    ->order_by('m_displayorder')
                                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    
    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('usergroup_priviledge', $Data)){
            log_message('debug', "Model Usergroup_priviledge_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Usergroup_priviledge_model/insert Error");
            return false;
        }
    }

    public function insert_batch($Data){
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item, $this->_Module);
        }
        if($this->HostDb->insert_batch('usergroup_priviledge', $Data)){
            log_message('debug', "Model Usergroup_priviledge_model/insert_batch Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Usergroup_priviledge_model/insert_batch Error");
            return false;
        }
    }
    
    public function delete($Pids, $Ugid){
        if(is_array($Pids)){
            $this->HostDb->where_in('up_priviledge_id', $Pids);
        }else{
            $this->HostDb->where('up_priviledge_id', $Pids);
        }
        $this->HostDb->where('up_usergroup_id', $Ugid);
        $this->HostDb->delete('usergroup_priviledge');
        $this->remove_cache($this->_Module);
        return true;
    }
    /**
     * 删除菜单时，同时删除权限
     * @param unknown $Where
     */
    public function delete_menu($Where){
        if(is_array($Where)){
            $Where = implode(',', $Where);
        }
        $this->HostDb->query("DELETE n9_usergroup_priviledge, n9_priviledge, n9_menu
            FROM n9_usergroup_priviledge LEFT JOIN n9_priviledge ON p_id = up_priviledge_id
            LEFT JOIN n9_menu ON m_id = p_source_id
            WHERE p_type = 'menu'
            WHERE u_id in ($Where)");
        $this->remove_cache('priviledge');
        $this->remove_cache('menu');
        return TRUE;
    }
    
    /**
     * 删除操作时，同时删除权限
     * @param unknown $Where
     */
    public function delete_operation($Where){
        if(is_array($Where)){
            $Where = implode(',', $Where);
        }
        $this->HostDb->query("DELETE n9_usergroup_priviledge, n9_priviledge, n9_operation
            FROM n9_usergroup_priviledge LEFT JOIN n9_priviledge ON p_id = up_priviledge_id
            LEFT JOIN n9_operation ON o_id = p_source_id
            WHERE p_type = 'operation'
            WHERE u_id in ($Where)");
        $this->remove_cache('priviledge');
        $this->remove_cache('operation');
        return TRUE;
    }
}
