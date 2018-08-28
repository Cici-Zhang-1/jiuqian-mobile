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
class Role_element_model extends MY_Model {
    public function __construct() {
        parent::__construct(__DIR__, __CLASS__);

        log_message('debug', 'Model permission/Role_element_model Start!');
    }

    public function select_by_rid($Rid) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_element')
                        ->where('re_role_id', $Rid)
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
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_element')
                ->join('element', 'e_id = re_element_id', 'left')
                ->join('card', 'c_id = e_card_id', 'left')
                ->join('menu', 'm_id = c_menu_id', 'left')
                ->join('role', 'r_id = re_role_id', 'left')
                ->join('usergroup_role', 'ur_role_id = r_id', 'left')
                ->where('ur_usergroup_id', $V)
                ->order_by('m_displayorder')
                ->group_by('e_id')->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的角色卡片元素';
            }
        }
        return $Return;
    }

    public function select_by_role_v($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $CardVs = $this->HostDb->select('rc_card_id')->from('role_card') // Menu
            ->where('rc_role_id', $V)->get_compiled_select();

            $RoleElement = $this->HostDb->select('re_id, re_element_id')->from('role_element') // RoleFunc
            ->where('re_role_id', $V)->get_compiled_select();
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('element')
                ->join('card', 'c_id = e_card_id', 'left')
                ->join('menu', 'm_id = c_menu_id', 'left')
                ->join('(' . $RoleElement . ') as A', 'A.re_element_id = e_id', 'left')
                ->where_in('c_id', $CardVs, false)
                ->order_by('m_displayorder')
                ->order_by('c_id')
                ->order_by('e_displayorder')
                ->get();
            /*$Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_element')
                ->join('element', 'e_id = re_element_id', 'left')
                ->where('re_role_id', $V)
                ->group_by('e_id')->get();*/
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的角色卡片元素';
            }
        }
        return $Return;
    }
    
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('role_element', $Data)){
            log_message('debug', "Model Role_element_model/insert_role_element Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Role_element_model/insert_role_element Error");
            return false;
        }
    }

    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('role_element', $Data)){
            log_message('debug', "Model Role_element_model/insert_batch Success!");
            $this->remove_cache($this->_Module);
            return true;
        }else{
            log_message('debug', "Model Role_element_model/insert_batch Error");
            return false;
        }
    }

    /**
     * 删除功能时同时删除相应的角色权限
     * @param $Mid
     * @return bool
     */
    public function delete_by_element_v($Mid){
        if(is_array($Mid)){
            $this->HostDb->where_in('re_element_id', $Mid);
        }else{
            $this->HostDb->where('re_element_id', $Mid);
        }
        $this->HostDb->delete('role_element');
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
            $this->HostDb->where_in('re_role_id', $Rid);
        }else{
            $this->HostDb->where('re_role_id', $Rid);
        }
        $this->HostDb->delete('role_element');
        $this->remove_cache($this->_Module);
        return true;
    }
}
