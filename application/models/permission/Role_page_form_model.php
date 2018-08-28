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
class Role_page_form_model extends MY_Model {
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
            $Query = $this->HostDb->select($Sql)->from('role_page_form')
                        ->where('rpf_role_id', $Rid)
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
            $Query = $this->HostDb->select($Sql)->from('role_page_form')
                ->join('page_form', 'pf_id = rpf_page_form_id', 'left')
                ->join('form_page', 'fp_id = pf_form_page_id', 'left')
                ->join('menu', 'm_id = fp_menu_id', 'left')
                ->join('role', 'r_id = rpf_role_id', 'left')
                ->join('usergroup_role', 'ur_role_id = r_id', 'left')
                ->where('ur_usergroup_id', $V)
                ->group_by('pf_id')->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的角色页面表单';
            }
        }
        return $Return;
    }

    public function select_by_role_v($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $FormPageVs = $this->HostDb->select('rfp_form_page_id')->from('role_form_page') // Menu
                                ->where('rfp_role_id', $V)->get_compiled_select();

            $RolePageForm = $this->HostDb->select('rpf_id, rpf_page_form_id')->from('role_page_form') // RoleFunc
                                ->where('rpf_role_id', $V)->get_compiled_select();
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('page_form')
                ->join('form_page', 'fp_id = pf_form_page_id', 'left')
                ->join('menu', 'm_id = fp_menu_id', 'left')
                ->join('(' . $RolePageForm . ') as A', 'A.rpf_page_form_id = pf_id', 'left')
                ->where_in('fp_id', $FormPageVs, false)
                ->order_by('m_displayorder')
                ->order_by('fp_id')
                ->get();
            /*$Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_page_form')
                ->join('page_form', 'pf_id = rpf_page_form_id', 'left')
                ->where('rpf_role_id', $V)
                ->group_by('pf_id')->get();*/
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的页面表单';
            }
        }
        return $Return;
    }


    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('role_page_form', $Data)){
            log_message('debug', "Model Role_page_form_model/insert_role_page_form Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Role_page_form_model/insert_role_page_form Error");
            return false;
        }
    }

    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item, $this->_Module);
        }
        if($this->HostDb->insert_batch('role_page_form', $Data)){
            log_message('debug', "Model Role_page_form_model/insert_batch Success!");
            $this->remove_cache($this->_Module);
            return true;
        }else{
            log_message('debug', "Model Role_page_form_model/insert_batch Error");
            return false;
        }
    }

    /**
     * 删除功能时同时删除相应的角色权限
     * @param $Mid
     * @return bool
     */
    public function delete_by_page_form_v($Mid){
        if(is_array($Mid)){
            $this->HostDb->where_in('rpf_page_form_id', $Mid);
        }else{
            $this->HostDb->where('rpf_page_form_id', $Mid);
        }
        $this->HostDb->delete('role_page_form');
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
            $this->HostDb->where_in('rpf_role_id', $Rid);
        }else{
            $this->HostDb->where('rpf_role_id', $Rid);
        }
        $this->HostDb->delete('role_page_form');
        $this->remove_cache($this->_Module);
        return true;
    }
}
