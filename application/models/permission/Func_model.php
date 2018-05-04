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
class Func_model extends MY_Model{
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Func_model Start!');
    }

    public function select() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('func')
                            ->join('menu', 'm_id = f_menu_id', 'left')
                            ->order_by('m_displayorder')
                            ->order_by('f_displayorder')
                        ->get();
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
            $Query = $this->HostDb->select($Sql)->from('func')
                ->where('f_menu_id', $Mid)
                ->order_by('f_displayorder')
                ->get();
            if($Query->num_rows() > 0){
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
            $this->HostDb->select($Sql)->from('role_func')
                ->join('func', 'f_id = rf_func_id', 'left')
                ->join('toggle_type AS TOGGLE', 'TOGGLE.tt_id = f_toggle', 'left')
                ->join('tag_type AS TAG', 'TAG.tt_id = f_tag', 'left')
                ->join('boolean_type', 'bt_id = f_multiple', 'left')
                ->join('modal_type', 'mt_id = f_modal_type', 'left');
            if ($Mid) {
                $this->HostDb->where('f_menu_id', $Mid);
            }
            $Query = $this->HostDb->where("rf_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")->group_by('f_id')->order_by('f_displayorder')->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    public function select_not_exist_operation($Operation) {
        $Query = $this->HostDb->select('f_id')
            ->from('func')
            ->where('f_url', $Operation)
            ->get();
        if ($Query->num_rows() > 0) {
            return false;
        }
        return true;
    }

    public function select_is_allowed_operation($Ugid, $Operation) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__ . $Ugid . $Operation;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_func')
                ->join('func', 'f_id = rf_func_id', 'left')
                ->where("rf_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")
                ->where('f_url', $Operation)
                ->group_by('f_id')
                ->order_by('f_displayorder')
                ->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    /**
     * 获取显示最大顺序
     * @return int
     */
    private function _select_max_displayorder($Mid){
        $Query = $this->HostDb->select_max('f_displayorder')
                        ->where('f_menu_id', $Mid)
                        ->get('func');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            return $Row['f_displayorder'] + 1;
        }else{
            return 1;
        }
    }

    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if(!empty($Data['f_displayorder'])){
            $this->_update_displayorder($Data['f_displayorder'], $Data['f_menu_id']);
        }else{
            $Data['f_displayorder'] = $this->_select_max_displayorder($Data['f_menu_id']);
        }
        if($this->HostDb->insert('func', $Data)){
            log_message('debug', "Model Func_model/insert Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Func_model/insert Error");
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

        $Query = $this->HostDb->select('f_displayorder')->where(array('f_id' => $Where))->get('func');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            if($Row['f_displayorder'] < $Data['f_displayorder']){
                $this->_update_displayorder_min($Row['f_displayorder'], $Data['f_displayorder'], $Data['f_menu_id']);
            }elseif ($Row['f_displayorder'] > $Data['f_displayorder']){
                $this->_update_displayorder_plus($Data['f_displayorder'], $Row['f_displayorder'], $Data['f_menu_id']);
            }
        }else{
            $GLOBALS['error'] = '您要修改的菜单不存在';
            return false;
        }
        $this->HostDb->where('f_id', $Where);
        $this->HostDb->update('func', $Data);
        $this->remove_cache($this->_Module);
        return TRUE;
    }

    private function _update_displayorder_min($Min, $Max, $Mid){
        $Query = $this->HostDb->query("UPDATE j_func SET f_displayorder = f_displayorder-1 where f_displayorder > $Min && f_displayorder <= $Max && f_menu_id = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }
    private function _update_displayorder_plus($Min, $Max, $Mid){
        $Query = $this->HostDb->query("UPDATE j_func SET f_displayorder = f_displayorder+1 where f_displayorder >= $Min && f_displayorder < $Max && f_menu_id = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 更新菜单的显示顺序
     * @param unknown $DisplayOrder
     * @return boolean
     */
    private function _update_displayorder($DisplayOrder, $Mid){
        $Query = $this->HostDb->query("UPDATE j_func SET f_displayorder = f_displayorder+1 where f_displayorder >= $DisplayOrder && f_menu_id = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }

    private function _delete_displayorder($DisplayOrder, $Mid){
        $Query = $this->HostDb->query("UPDATE j_func SET f_displayorder = f_displayorder-1 where f_displayorder > $DisplayOrder && f_menu_id = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 在删除用户组时，删除冗余的用户组角色信息
     * 在设置用户组包含角色时，也需要删除冗余信息
     * @param $Where
     * @return bool
     */
    public function delete($Where) {
        if(is_array($Where)){
            foreach ($Where as $key => $value){
                $Query = $this->HostDb->select('f_displayorder, f_menu_id')->where(array('f_id' => $value))->get('func');
                if($Query->num_rows() > 0){
                    $Row = $Query->row_array();
                    $Query->free_result();
                    $this->_delete_displayorder($Row['f_displayorder'], $Row['f_menu_id']);
                }
            }
        }else{
            $Query = $this->HostDb->select('f_displayorder, f_menu_id')->where(array('f_id' => $Where))->get('func');
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $this->_delete_displayorder($Row['f_displayorder'], $Row['f_menu_id']);
            }
        }
        if(is_array($Where)){
            $this->HostDb->where_in('f_id', $Where);
        }else{
            $this->HostDb->where('f_id', $Where);
        }

        $this->HostDb->delete('func');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 删除菜单时，需要删除包含的功能
     * @param $Where
     */
    public function delete_by_mid($Where) {
        if (is_array($Where)) {
            $Query = $this->HostDb->select('f_id')->from('func')
                            ->where_in('f_menu_id', $Where)->get();
        }else {
            $Query = $this->HostDb->select('f_id')->from('func')
                            ->where('f_menu_id', $Where)->get();
        }
        if ($Query->num_rows() > 0) {
            $Fids = $Query->result_array();
            $Query->free_result();
        }else {
            $Fids = false;
        }
        if(is_array($Where)){
            $this->HostDb->where_in('f_menu_id', $Where);
        }else{
            $this->HostDb->where('f_menu_id', $Where);
        }

        $this->HostDb->delete('func');
        if ($Fids) {
            foreach ($Fids as $key => $value) {
                $Fids[$key] = $value['f_id'];
            }
            $this->load->model('permission/role_func_model');
            $this->load->model('permission/form_model');
            return $this->role_func_model->delete_by_fid($Fids) && $this->form_model->delete_by_func_id($Fids);
        }else {
            return true;
        }
    }
}
