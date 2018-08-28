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
class Role_card_model extends MY_Model {
    public function __construct() {
        parent::__construct(__DIR__, __CLASS__);

        log_message('debug', 'Model permission/Role_card_model Start!');
    }

    public function select_by_rid($Rid) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_card')
                        ->where('rc_role_id', $Rid)
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
            $Query = $this->HostDb->select($Sql)->from('role_card')
                ->join('card', 'c_id = rc_card_id', 'left')
                ->join('menu', 'm_id = c_menu_id', 'left')
                ->join('role', 'r_id = rc_role_id', 'left')
                ->join('usergroup_role', 'ur_role_id = r_id', 'left')
                ->where('ur_usergroup_id', $V)
                ->order_by('m_displayorder')
                ->group_by('c_id')->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的角色卡片';
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

            $RoleCard = $this->HostDb->select('rc_id, rc_card_id')->from('role_card') // RoleFunc
                            ->where('rc_role_id', $V)->get_compiled_select();
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('card')
                ->join('menu', 'm_id = c_menu_id', 'left')
                ->join('(' . $RoleCard . ') as A', 'A.rc_card_id = c_id', 'left')
                ->where_in('m_id', $Mids, false)
                ->order_by('m_displayorder')
                ->get();
            /*$Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_card')
                ->join('card', 'c_id = rc_card_id', 'left')
                ->where('rc_role_id', $V)
                ->group_by('c_id')->get();*/
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的卡片';
            }
        }
        return $Return;
    }

    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('role_card', $Data)){
            log_message('debug', "Model Role_card_model/insert_role_card Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Role_card_model/insert_role_card Error");
            return false;
        }
    }

    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('role_card', $Data)){
            log_message('debug', "Model Role_card_model/insert_batch Success!");
            $this->remove_cache($this->_Module);
            return true;
        }else{
            log_message('debug', "Model Role_card_model/insert_batch Error");
            return false;
        }
    }

    /**
     * 删除功能时同时删除相应的角色权限
     * @param $Mid
     * @return bool
     */
    public function delete_by_card_v($Mid){
        if(is_array($Mid)){
            $this->HostDb->where_in('rc_card_id', $Mid);
        }else{
            $this->HostDb->where('rc_card_id', $Mid);
        }
        $this->HostDb->delete('role_card');
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
            $this->HostDb->where_in('rc_role_id', $Rid);
        }else{
            $this->HostDb->where('rc_role_id', $Rid);
        }
        $this->HostDb->delete('role_card');
        $this->remove_cache($this->_Module);
        return true;
    }
}
