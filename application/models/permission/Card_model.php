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

    public function select($Search){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.implode('_', $Search).__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Search['pn'] = $this->_page_num($Search);
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $this->HostDb->select($Sql)->from('card')
                    ->join('menu', 'm_id = c_menu_id', 'left');
                if (isset($Search['v']) && $Search['v'] != '') {
                    $this->HostDb->where('c_menu_id', $Search['v']);
                }
                if(!empty($Con['keyword'])){
                    $this->HostDb->group_start()
                        ->like('c_name', $Search['keyword'])
                        ->group_end();
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
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
        $this->HostDb->select('count(c_id) as num', FALSE);
        $this->HostDb->from('card');
        if (isset($Search['v']) && $Search['v'] != '') {
            $this->HostDb->where('c_menu_id', $Search['v']);
        }

        if(!empty($Con['keyword'])){
            $this->HostDb->group_start()
                ->like('c_name', $Search['keyword'])
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
}
