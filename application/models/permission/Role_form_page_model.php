<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Role_form_page_model Model
 *
 * @package  CodeIgniter
 * @category Model
 */
class Role_form_page_model extends MY_Model {
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Role_form_page_model Start!');
    }

    /**
     * Select from table role_form_page
     */
    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('role_form_page')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的角色表单页面';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(rfp_id) as num', FALSE);
        $this->HostDb->from('role_form_page');

        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Search['pagesize']) == 0){
                $Pn = intval($Row['num']/$Search['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Search['pagesize'])+1;
            }
            return $Pn;
        }else{
            return false;
        }
    }
    
    public function select_by_usergroup_v($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_form_page')
                ->join('form_page', 'fp_id = rfp_form_page_id', 'left')
                ->join('menu', 'm_id = fp_menu_id', 'left')
                ->join('role', 'r_id = rfp_role_id', 'left')
                ->join('usergroup_role', 'ur_role_id = r_id', 'left')
                ->where('ur_usergroup_id', $V)
                ->order_by('m_displayorder')
                ->group_by('fp_id')->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的角色表单页面';
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

            $RoleFormPage = $this->HostDb->select('rfp_id, rfp_form_page_id')->from('role_form_page') // RoleFunc
                    ->where('rfp_role_id', $V)->get_compiled_select();
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('form_page')
                ->join('menu', 'm_id = fp_menu_id', 'left')
                ->join('(' . $RoleFormPage . ') as A', 'A.rfp_form_page_id = fp_id', 'left')
                ->where_in('m_id', $Mids, false)
                ->order_by('m_displayorder')
                ->get();
            /*$Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_form_page')
                ->join('form_page', 'fp_id = rfp_form_page_id', 'left')
                ->where('rfp_role_id', $V)
                ->group_by('fp_id')->get();*/
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的角色表单页面';
            }
        }
        return $Return;
    }

    /**
     * Insert data to table role_form_page
     * @param $Data
     * @return Insert_id | Boolean
     */
    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('role_form_page', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '插入角色表单页面数据失败!';
            return false;
        }
    }

    /**
     * Insert batch data to table role_form_page
     */
    public function insert_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format($value, $Item);
        }
        if($this->HostDb->insert_batch('role_form_page', $Data)){
            $this->remove_cache($this->_Module);
            return true;
        } else {
            $GLOBALS['error'] = '插入角色表单页面数据失败!';
            return false;
        }
    }

    /**
     * Update the data of table role_form_page
     * @param $Data
     * @param $Where
     * @return boolean
     */
    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        if (is_array($Where)) {
            $this->HostDb->where_in('rfp_id', $Where);
        } else {
            $this->HostDb->where('rfp_id', $Where);
        }
        $this->HostDb->update('role_form_page', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 批量更新table role_form_page
     */
    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('role_form_page', $Data, 'rfp_id');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * Delete data from table role_form_page
     * @param $Where
     * @return boolean
     */
    public function delete($Where) {
        if(is_array($Where)){
            $this->HostDb->where_in('rfp_id', $Where);
        } else {
            $this->HostDb->where('rfp_id', $Where);
        }

        $this->HostDb->delete('role_form_page');
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
            $this->HostDb->where_in('rfp_role_id', $Rid);
        }else{
            $this->HostDb->where('rfp_role_id', $Rid);
        }
        $this->HostDb->delete('role_form_page');
        $this->remove_cache($this->_Module);
        return true;
    }
}
