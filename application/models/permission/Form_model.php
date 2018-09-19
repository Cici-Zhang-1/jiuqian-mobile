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
class Form_model extends MY_Model{
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Form_model Start!');
    }

    public function select($Search){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.implode('_', $Search).__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('form AS A')
                    ->join('func AS B', 'B.f_id = A.f_func_id', 'left')
                    ->join('menu', 'm_id = B.f_menu_id', 'left');
                if (isset($Search['v']) && $Search['v'] != '') {
                    $this->HostDb->where('A.f_func_id', $Search['v']);
                }
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                        ->like('f_name', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->order_by('A.f_displayorder')->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有符合搜索条件的功能表单页';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(f_id) as num', FALSE);
        $this->HostDb->from('form');
        if (isset($Search['v']) && $Search['v'] != '') {
            $this->HostDb->where('f_func_id', $Search['v']);
        }

        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                ->like('f_name', $Search['keyword'])
                ->group_end();
        }

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

    public function select_allowed($Ugid, $Mid = 0, $Fid = 0) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Ugid . $Mid . $Fid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('role_form')
                ->join('form AS A', 'A.f_id = rf_form_id', 'left')
                ->join('form_type', 'ft_name = A.f_form_type', 'left')
                ->join('boolean_type AS READONLY', 'READONLY.bt_name = A.f_readonly', 'left')
                ->join('boolean_type AS REQUIRED', 'REQUIRED.bt_name = A.f_required', 'left')
                ->join('boolean_type AS MULTIPLE', 'MULTIPLE.bt_name = A.f_multiple', 'left')
                ->join('func as B', 'B.f_id = A.f_func_id', 'left');
            if ($Mid) {
                $this->HostDb->where('B.f_menu_id', $Mid);
            }
            if ($Fid) {
                $this->HostDb->where('A.f_func_id', $Fid);
            }
            $Query = $this->HostDb->where("rf_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")->group_by('A.f_id')
                ->order_by('A.f_displayorder')->get();
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
    public function select_by_fid($Fid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('form')
                ->where('f_func_id', $Fid)
                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    /**
     * 获取显示最大顺序
     * @return int
     */
    private function _select_max_displayorder($Fid){
        $Query = $this->HostDb->select_max('f_displayorder')
            ->where('f_func_id', $Fid)
            ->get('form');
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
            $this->_update_displayorder($Data['f_displayorder'], $Data['f_func_id']);
        }else{
            $Data['f_displayorder'] = $this->_select_max_displayorder($Data['f_func_id']);
        }
        if($this->HostDb->insert('form', $Data)){
            log_message('debug', "Model Form_model/insert Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Form_model/insert Error");
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

        $Query = $this->HostDb->select('f_displayorder')->where(array('f_id' => $Where))->get('form');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            if($Row['f_displayorder'] < $Data['f_displayorder']){
                $this->_update_displayorder_min($Row['f_displayorder'], $Data['f_displayorder'], $Data['f_func_id']);
            }elseif ($Row['f_displayorder'] > $Data['f_displayorder']){
                $this->_update_displayorder_plus($Data['f_displayorder'], $Row['f_displayorder'], $Data['f_func_id']);
            }
        }else{
            $GLOBALS['error'] = '您要修改的表单不存在';
            return false;
        }
        $this->HostDb->where('f_id', $Where);
        $this->HostDb->update('form', $Data);
        $this->remove_cache($this->_Module);
        return TRUE;
    }

    private function _update_displayorder_min($Min, $Max, $Mid){
        $Query = $this->HostDb->query("UPDATE j_form SET f_displayorder = f_displayorder-1 where f_displayorder > $Min && f_displayorder <= $Max && f_func_id = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }
    private function _update_displayorder_plus($Min, $Max, $Mid){
        $Query = $this->HostDb->query("UPDATE j_form SET f_displayorder = f_displayorder+1 where f_displayorder >= $Min && f_displayorder < $Max && f_func_id = $Mid");
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
        $Query = $this->HostDb->query("UPDATE j_form SET f_displayorder = f_displayorder+1 where f_displayorder >= $DisplayOrder && f_func_id = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }

    private function _delete_displayorder($DisplayOrder, $Mid){
        $Query = $this->HostDb->query("UPDATE j_form SET f_displayorder = f_displayorder-1 where f_displayorder > $DisplayOrder && f_func_id = $Mid");
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
                $Query = $this->HostDb->select('f_displayorder, f_func_id')->where(array('f_id' => $value))->get('form');
                if($Query->num_rows() > 0){
                    $Row = $Query->row_array();
                    $Query->free_result();
                    $this->_delete_displayorder($Row['f_displayorder'], $Row['f_func_id']);
                }
            }
        }else{
            $Query = $this->HostDb->select('f_displayorder, f_func_id')->where(array('f_id' => $Where))->get('form');
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $this->_delete_displayorder($Row['f_displayorder'], $Row['f_func_id']);
            }
        }
        if(is_array($Where)){
            $this->HostDb->where_in('f_id', $Where);
        }else{
            $this->HostDb->where('f_id', $Where);
        }

        $this->HostDb->delete('form');
        $this->remove_cache($this->_Module);
        return true;
    }
}
