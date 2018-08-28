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
class Page_form_model extends MY_Model{
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Page_form_model Start!');
    }

    public function select($Search){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.implode('_', $Search).__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('page_form')
                    ->join('form_page', 'fp_id = pf_form_page_id', 'left');
                if (isset($Search['v']) && $Search['v'] != '') {
                    $this->HostDb->where('fp_id', $Search['v']);
                }
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                        ->like('pf_name', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->order_by('pf_displayorder')->get();
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Search['p'],
                    'pn' => $Search['pn'],
                    'pagesize' => $Search['pagesize']
                );
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有符合搜索条件的卡片页';
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(pf_id) as num', FALSE);
        $this->HostDb->from('page_form');
        if (isset($Search['v']) && $Search['v'] != '') {
            $this->HostDb->where('pf_form_page_id', $Search['v']);
        }

        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                ->like('pf_name', $Search['keyword'])
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

    public function select_allowed($Ugid, $Mid = 0, $Fpid = 0) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $Ugid . $Mid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $this->HostDb->select($Sql)->from('role_page_form')
                    ->join('page_form', 'pf_id = rpf_page_form_id')
                    ->join('form_page', 'fp_id = pf_form_page_id')
                    ->join('form_type', 'ft_name = pf_form_type', 'left')
                    ->join('boolean_type AS READONLY', 'READONLY.bt_name = pf_readonly', 'left')
                    ->join('boolean_type AS REQUIRED', 'REQUIRED.bt_name = pf_required', 'left')
                    ->join('boolean_type AS MULTIPLE', 'MULTIPLE.bt_name = pf_multiple', 'left');
            if ($Mid) {
                $this->HostDb->where('fp_menu_id', $Mid);
            }
            if ($Fpid) {
                $this->HostDb->where('fp_id', $Fpid);
            }

            $Query = $this->HostDb->where("rpf_role_id in (SELECT ur_role_id FROM j_usergroup_role WHERE ur_usergroup_id = $Ugid)")->group_by('pf_id')
                ->order_by('pf_displayorder')->get();
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
    public function select_by_fpid($Fpid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('page_form')
                ->where('pf_form_page_id', $Fpid)
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
    private function _select_max_displayorder($Fpid){
        $Query = $this->HostDb->select_max('pf_displayorder')
            ->where('pf_form_page_id', $Fpid)
            ->get('page_form');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            return $Row['pf_displayorder'] + 1;
        }else{
            return 1;
        }
    }

    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item, $this->_Module);

        if(!empty($Data['pf_displayorder'])){
            $this->_update_displayorder($Data['pf_displayorder'], $Data['pf_form_page_id']);
        }else{
            $Data['pf_displayorder'] = $this->_select_max_displayorder($Data['pf_form_page_id']);
        }
        if($this->HostDb->insert('page_form', $Data)){
            log_message('debug', "Model Page_form_model/insert Success!");
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Page_form_model/insert Error");
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
        $Data = $this->_format_re($Data, $Item, $this->_Module);

        $Query = $this->HostDb->select('pf_displayorder')->where(array('pf_id' => $Where))->get('page_form');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            if($Row['pf_displayorder'] < $Data['pf_displayorder']){
                $this->_update_displayorder_min($Row['pf_displayorder'], $Data['pf_displayorder'], $Data['pf_form_page_id']);
            }elseif ($Row['pf_displayorder'] > $Data['pf_displayorder']){
                $this->_update_displayorder_plus($Data['pf_displayorder'], $Row['pf_displayorder'], $Data['pf_form_page_id']);
            }
        }else{
            $GLOBALS['error'] = '您要修改的表单不存在';
            return false;
        }

        $this->HostDb->where('pf_id', $Where);
        $this->HostDb->update('page_form', $Data);
        $this->remove_cache($this->_Module);
        return TRUE;
    }


    private function _update_displayorder_min($Min, $Max, $Mid){
        $Query = $this->HostDb->query("UPDATE j_page_form SET pf_displayorder = pf_displayorder-1 where pf_displayorder > $Min && pf_displayorder <= $Max && pf_form_page_id = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }
    private function _update_displayorder_plus($Min, $Max, $Mid){
        $Query = $this->HostDb->query("UPDATE j_page_form SET pf_displayorder = pf_displayorder+1 where pf_displayorder >= $Min && pf_displayorder < $Max && pf_form_page_id = $Mid");
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
        $Query = $this->HostDb->query("UPDATE j_page_form SET pf_displayorder = pf_displayorder+1 where pf_displayorder >= $DisplayOrder && pf_form_page_id = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }

    private function _delete_displayorder($DisplayOrder, $Mid){
        $Query = $this->HostDb->query("UPDATE j_page_form SET pf_displayorder = pf_displayorder-1 where pf_displayorder > $DisplayOrder && pf_form_page_id = $Mid");
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
                $Query = $this->HostDb->select('pf_displayorder, pf_form_page_id')->where(array('pf_id' => $value))->get('j_page_form');
                if($Query->num_rows() > 0){
                    $Row = $Query->row_array();
                    $Query->free_result();
                    $this->_delete_displayorder($Row['pf_displayorder'], $Row['pf_form_page_id']);
                }
            }
        }else{
            $Query = $this->HostDb->select('pf_displayorder, pf_form_page_id')->where(array('pf_id' => $Where))->get('j_page_form');
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $this->_delete_displayorder($Row['pf_displayorder'], $Row['pf_form_page_id']);
            }
        }

        if(is_array($Where)){
            $this->HostDb->where_in('pf_id', $Where);
        }else{
            $this->HostDb->where('pf_id', $Where);
        }

        $this->HostDb->delete('page_form');
        $this->remove_cache($this->_Module);
        return true;
    }
}
