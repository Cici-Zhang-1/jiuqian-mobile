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

    public function select($Search){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.implode('_', $Search).__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('element')
                    ->join('card', 'c_id = e_card_id', 'left')
                    ->join('menu', 'm_id = c_menu_id', 'left');
                if (isset($Search['v']) && $Search['v'] != '') {
                    $this->HostDb->where('e_card_id', $Search['v']);
                }
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                        ->like('e_name', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->order_by('e_displayorder')->get();
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
        $this->HostDb->select('count(e_id) as num', FALSE);
        $this->HostDb->from('element');
        if (isset($Search['v']) && $Search['v'] != '') {
            $this->HostDb->where('e_card_id', $Search['v']);
        }

        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                ->like('e_name', $Search['keyword'])
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
                ->join('boolean_type', 'bt_name = e_checked', 'left')
                ->join('card', 'c_id = e_card_id', 'left');
            if ($Mid) {
                $this->HostDb->where('c_menu_id', $Mid);
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

    /**
     * 获取显示最大顺序
     * @return int
     */
    private function _select_max_displayorder($Cid){
        $Query = $this->HostDb->select_max('e_displayorder')
            ->where('e_card_id', $Cid)
            ->get('element');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            return $Row['e_displayorder'] + 1;
        }else{
            return 1;
        }
    }
    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if(!empty($Data['e_displayorder'])){
            $this->_update_displayorder($Data['e_displayorder'], $Data['e_card_id']);
        }else{
            $Data['e_displayorder'] = $this->_select_max_displayorder($Data['e_card_id']);
        }
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

        $Query = $this->HostDb->select('e_displayorder')->where(array('e_id' => $Where))->get('element');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            if($Row['e_displayorder'] < $Data['e_displayorder']){
                $this->_update_displayorder_min($Row['e_displayorder'], $Data['e_displayorder'], $Data['e_card_id']);
            }elseif ($Row['e_displayorder'] > $Data['e_displayorder']){
                $this->_update_displayorder_plus($Data['e_displayorder'], $Row['e_displayorder'], $Data['e_card_id']);
            }
        }else{
            $GLOBALS['error'] = '您要修改的元素不存在';
            return false;
        }
        $this->HostDb->where('e_id', $Where);
        $this->HostDb->update('element', $Data);
        $this->remove_cache($this->_Module);
        return TRUE;
    }


    private function _update_displayorder_min($Min, $Max, $Mid){
        $Query = $this->HostDb->query("UPDATE j_element SET e_displayorder = e_displayorder-1 where e_displayorder > $Min && e_displayorder <= $Max && e_card_id = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }
    private function _update_displayorder_plus($Min, $Max, $Mid){
        $Query = $this->HostDb->query("UPDATE j_element SET e_displayorder = e_displayorder+1 where e_displayorder >= $Min && e_displayorder < $Max && e_card_id = $Mid");
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
        $Query = $this->HostDb->query("UPDATE j_element SET e_displayorder = e_displayorder+1 where e_displayorder >= $DisplayOrder && e_card_id = $Mid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }

    private function _delete_displayorder($DisplayOrder, $Mid){
        $Query = $this->HostDb->query("UPDATE j_element SET e_displayorder = e_displayorder-1 where e_displayorder > $DisplayOrder && e_card_id = $Mid");
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
                $Query = $this->HostDb->select('e_displayorder, e_card_id')->where(array('e_id' => $value))->get('element');
                if($Query->num_rows() > 0){
                    $Row = $Query->row_array();
                    $Query->free_result();
                    $this->_delete_displayorder($Row['e_displayorder'], $Row['e_card_id']);
                }
            }
        }else{
            $Query = $this->HostDb->select('e_displayorder, e_card_id')->where(array('e_id' => $Where))->get('element');
            if($Query->num_rows() > 0){
                $Row = $Query->row_array();
                $Query->free_result();
                $this->_delete_displayorder($Row['e_displayorder'], $Row['e_card_id']);
            }
        }

        if(is_array($Where)){
            $this->HostDb->where_in('e_id', $Where);
        }else{
            $this->HostDb->where('e_id', $Where);
        }

        $this->HostDb->delete('element');
        $this->remove_cache($this->_Module);
        return true;
    }
}
