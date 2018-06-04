<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/13
 * Time: 09:58
 *
 * Desc:
 */
class Card_model extends MY_Model{
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Card_model Start!');
    }

    public function select() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('card')
                ->join('menu', 'm_id = c_menu_id', 'left')
                ->order_by('m_displayorder')
                ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    /**
     * METHOD: 获得许可的card
     * @param $Ugid
     * @param int $Mid
     * @return bool
     */
    public function select_allowed($Ugid, $Mid = 0) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Ugid . $Mid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('role_card')
                ->join('card', 'c_id = rc_card_id')
                ->join('card_type', 'ct_name = c_card_type', 'left')
                ->join('card_setting', 'cs_name = c_card_setting', 'left');
            if ($Mid) {
                $this->HostDb->where('c_menu_id', $Mid);
            }
            $Query = $this->HostDb->where("rc_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")->group_by('c_id')->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    /**
     * 通过用户组id获取数据
     * @param $Uid
     * @return bool
     */
    public function select_by_mid($Mid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('card')
                ->where('c_menu_id', $Mid)
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
        if($this->HostDb->insert('card', $Data)){
            log_message('debug', "Model Card_model/insert Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Card_model/insert Error");
            return false;
        }
    }

    /**
     * 更新菜单
     * @param unknown $Data
     * @param unknown $Where
     */
    public function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);

        $this->HostDb->where('c_id', $Where);
        $this->HostDb->update('card', $Data);
        $this->remove_cache($this->_Module);
        return TRUE;
    }

    /**
     * 在删除用户组时，删除冗余的用户组角色信息
     * 在设置用户组包含角色时，也需要删除冗余信息
     * @param $Where
     * @return bool
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('c_id', $Where);
        }else{
            $this->HostDb->where('c_id', $Where);
        }

        $this->HostDb->delete('card');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 删除菜单时，需要删除包含的功能
     * @param $Where
     */
    public function delete_by_mid($Where) {
        if (is_array($Where)) {
            $Query = $this->HostDb->select('c_id')->from('card')
                ->where_in('c_menu_id', $Where)->get();
        }else {
            $Query = $this->HostDb->select('c_id')->from('card')
                ->where('c_menu_id', $Where)->get();
        }
        if ($Query->num_rows() > 0) {
            $Cids = $Query->result_array();
            $Query->free_result();
        }else {
            $Cids = false;
        }
        if(is_array($Where)){
            $this->HostDb->where_in('c_menu_id', $Where);
        }else{
            $this->HostDb->where('c_menu_id', $Where);
        }

        $this->HostDb->delete('card');
        if ($Cids) {
            foreach ($Cids as $key => $value) {
                $Cids[$key] = $value['c_id'];
            }
            $this->load->model('permission/role_card_model');
            $this->load->model('permission/element_model');
            return $this->role_card_model->delete_by_cid($Cids) && $this->element_model->delete_by_cid($Cids);
        }else {
            return true;
        }
    }
}
