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
class Element_model extends MY_Model {
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Element_model Start!');
    }

    public function select() {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('element')
                ->join('card', 'c_id = e_card_id', 'left')
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
     * METHOD: 获取许可元素
     * @param $Ugid
     * @param $Mid
     * @return bool
     */
    public function select_allowed($Ugid, $Mid = 0, $Cid = 0) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Ugid . $Mid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('role_element')
                ->join('element', 'e_id = re_element_id')
                ->join('boolean_type', 'bt_name = e_checked', 'left');
            if ($Mid) {
                $this->HostDb->join('card', 'c_id = e_card_id', 'left')->where('c_menu_id', $Mid);
            }
            if ($Cid) {
                $this->HostDb->where('e_card_id', $Cid);
            }
            $Query = $this->HostDb->where("re_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")->group_by('e_id')
                ->order_by('e_displayorder')->get();
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
    public function select_by_cid($Cid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('element')
                            ->where('e_card_id', $Cid)
                            ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    public function select_by_card_url($Ugid, $CardUrl) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Ugid . $CardUrl;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('role_element')
                ->join('element', 'e_id = re_element_id')
                ->join('card', 'c_id = e_card_id', 'left')
                ->where('c_url', $CardUrl)
                ->where("re_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")
                ->group_by('e_id')
            ->get();

            if ($Query->num_rows() > 0) {
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('element', $Data)){
            log_message('debug', "Model Element_model/insert Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Element_model/insert Error");
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

        $this->HostDb->where('e_id', $Where);
        $this->HostDb->update('element', $Data);
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
            $this->HostDb->where_in('e_id', $Where);
        }else{
            $this->HostDb->where('e_id', $Where);
        }

        $this->HostDb->delete('element');
        $this->remove_cache($this->_Module);
        return true;
    }

    /**
     * 删除菜单时，需要删除包含的功能
     * @param $Where
     */
    public function delete_by_cid($Where) {
        if (is_array($Where)) {
            $Query = $this->HostDb->select('e_id')->from('element')
                ->where_in('e_card_id', $Where)->get();
        }else {
            $Query = $this->HostDb->select('e_id')->from('card')
                ->where('e_card_id', $Where)->get();
        }
        if ($Query->num_rows() > 0) {
            $Eids = $Query->result_array();
            $Query->free_result();
        }else {
            $Eids = false;
        }
        if(is_array($Where)){
            $this->HostDb->where_in('e_card_id', $Where);
        }else{
            $this->HostDb->where('e_card_id', $Where);
        }

        $this->HostDb->delete('element');
        if ($Eids) {
            foreach ($Eids as $key => $value) {
                $Eids[$key] = $value['e_id'];
            }
            $this->load->model('permission/role_element_model');
            return $this->role_element_model->delete_by_eid($Eids);
        }else {
            return true;
        }
    }
}
