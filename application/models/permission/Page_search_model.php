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
class Page_search_model extends MY_Model{
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Page_search_model Start!');
    }

    public function select() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('page_search')
                            ->join('menu', 'm_id = ps_menu_id', 'left')
                            ->order_by('m_displayorder')
                        ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    public function select_allowed($Ugid, $Mid = 0) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Ugid . $Mid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('role_page_search')
                    ->join('page_search', 'ps_id = rps_page_search_id')
                    ->join('form_type as FORMTYPE', 'FORMTYPE.ft_name = ps_form_type', 'left')
                    ->join('form_type as TYPE', 'TYPE.ft_name = ps_type', 'left')
                    ->join('boolean_type AS READONLY', 'READONLY.bt_name = ps_readonly', 'left')
                    ->join('boolean_type AS REQUIRED', 'REQUIRED.bt_name = ps_required', 'left')
                    ->join('boolean_type AS MULTIPLE', 'MULTIPLE.bt_name = ps_multiple', 'left');
            if ($Mid) {
                $this->HostDb->where('ps_menu_id', $Mid);
            }
            $Query = $this->HostDb->where("rps_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")->group_by('ps_id')->get();
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
            $Query = $this->HostDb->select($Sql)->from('page_search')
                ->where('ps_menu_id', $Mid)
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

        if($this->HostDb->insert('page_search', $Data)){
            log_message('debug', "Model Page_search_model/insert Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Page_search_model/insert Error");
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

        $this->HostDb->where('ps_id', $Where);
        $this->HostDb->update('page_search', $Data);
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
            $this->HostDb->where_in('ps_id', $Where);
        }else{
            $this->HostDb->where('ps_id', $Where);
        }

        $this->HostDb->delete('page_search');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 删除菜单时，需要删除包含的功能
     * @param $Where
     */
    public function delete_by_mid($Where) {
        if (is_array($Where)) {
            $Query = $this->HostDb->select('ps_id')->from('page_search')
                            ->where_in('ps_menu_id', $Where)->get();
        }else {
            $Query = $this->HostDb->select('ps_id')->from('page_search')
                            ->where('ps_menu_id', $Where)->get();
        }
        if ($Query->num_rows() > 0) {
            $Psids = $Query->result_array();
            $Query->free_result();
        }else {
            $Psids = false;
        }
        if(is_array($Where)){
            $this->HostDb->where_in('ps_menu_id', $Where);
        }else{
            $this->HostDb->where('ps_menu_id', $Where);
        }

        $this->HostDb->delete('func');
        if ($Psids) {
            foreach ($Psids as $key => $value) {
                $Psids[$key] = $value['ps_id'];
            }
            $this->load->model('permission/role_page_search_model');
            return $this->role_page_search_model->delete_by_psid($Psids);
        }else {
            return true;
        }
    }
}
