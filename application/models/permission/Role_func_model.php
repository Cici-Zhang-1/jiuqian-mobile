<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/12
 * Time: 12:30
 *
 * Desc:
 */
class Role_func_model extends MY_Model {
    public function __construct() {
        parent::__construct(__DIR__, __CLASS__);

        log_message('debug', 'Model permission/Role_func_model Start!');
    }

    public function select_by_rid($Rid) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_func')
                        ->where('rf_role_id', $Rid)
                        ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    public function select_by_usergroup_v($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {

            /*$Mids = $this->HostDb->select('m_id')->from('role_menu')
                ->where('rm_role_id', $V)->get_compiled_select();

            $RoleFunc = $this->HostDb->select('rf_id, rf_func_id')->from('role_func')
                ->where('rf_role_id', $V)->get_compiled_select();
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('func')
                ->join('(' . $RoleFunc . ') as A', 'A.rf_func_id = f_id', 'left')
                ->join('menu', 'm_id = f_menu_id', 'left')
                ->where_in('m_id', $Mids, false)
                ->order_by('m_displayorder')
                ->get();*/
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_func')
                ->join('func', 'f_id = rf_func_id', 'left')
                ->join('menu', 'm_id = f_menu_id', 'left')
                ->join('role', 'r_id = rf_role_id', 'left')
                ->join('usergroup_role', 'ur_role_id = r_id', 'left')
                ->where('ur_usergroup_id', $V)
                ->order_by('m_displayorder')
                ->group_by('f_id')->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的角色功能';
            }
        }
        return $Return;
    }

    public function select_by_role_v($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Mids = $this->HostDb->select('rm_menu_id')->from('role_menu') // Menu
                ->where('rm_role_id', $V)->get_compiled_select();

            $RoleFunc = $this->HostDb->select('rf_id, rf_func_id')->from('role_func') // RoleFunc
                ->where('rf_role_id', $V)->get_compiled_select();
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('func')
                ->join('menu', 'm_id = f_menu_id', 'left')
                ->join('(' . $RoleFunc . ') as A', 'A.rf_func_id = f_id', 'left')
                ->where_in('m_id', $Mids, false)
                ->order_by('m_displayorder')
                ->get();
            /*$Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_func')
                ->join('func', 'f_id = rf_func_id', 'left')
                ->where('rf_role_id', $V)
                ->group_by('f_id')->get();*/
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的功能';
            }
        }
        return $Return;
    }

    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('role_func', $Data)){
            log_message('debug', "Model Role_func_model/insert_role_func Success!");
            $this->remove_cache('menu');
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Role_func_model/insert_role_func Error");
            return false;
        }
    }

    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('role_func', $Data)){
            log_message('debug', "Model Role_func_model/insert_batch Success!");
            $this->remove_cache($this->_Module);
            return true;
        }else{
            log_message('debug', "Model Role_func_model/insert_batch Error");
            return false;
        }
    }

    /**
     * 删除功能时同时删除相应的角色权限
     * @param $Mid
     * @return bool
     */
    public function delete_by_func_v($Where){
        if(is_array($Where)){
            $this->HostDb->where_in('rf_func_id', $Where);
        }else{
            $this->HostDb->where('rf_func_id', $Where);
        }
        $this->HostDb->delete('role_func');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 删除角色时同时删除相关联的功能权限
     * @param $Rid
     * @return boolean
     */
    public function delete_by_role_v($Rid) {
        if(is_array($Rid)){
            $this->HostDb->where_in('rf_role_id', $Rid);
        }else{
            $this->HostDb->where('rf_role_id', $Rid);
        }
        $this->HostDb->delete('role_func');
        $this->remove_cache($this->_Module);
        return true;
    }
}
