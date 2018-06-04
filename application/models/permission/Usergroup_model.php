<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月5日
 * @author Zhangcc
 * @version
 * @des
 * 用户组
 */
class Usergroup_model extends MY_Model{
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model permission/Usergroup_model Start!');
    }

    public function select($Search) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . implode('_', $Search);
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            if(empty($Search['pn'])){
                $Search['pn'] = $this->_page_num($Search);
            }else{
                $this->_Num = $Search['num'];
            }
            if(!empty($Search['pn'])){
                $Sql = $this->_unformat_as($Item);
                $Query = $this->HostDb->select($Sql)->from('usergroup')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
                if ($Query->num_rows() > 0) {
                    $Return = array(
                        'content' => $Query->result_array(),
                        'num' => $this->_Num,
                        'p' => $Search['p'],
                        'pn' => $Search['pn']
                    );
                    $this->cache->save($Cache, $Return, MONTHS);
                } else {
                    $GLOBALS['error'] = '没有符合搜索条件的用户组';
                }
            }
        }
        return $Return;
    }

    private function _page_num($Search){
        $this->HostDb->select('count(u_id) as num', FALSE);
        $this->HostDb->from('usergroup');

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

    public function select_usergroup_id($Name){
        $Query = $this->HostDb->select('u_id')->from('usergroup')->where('u_name', $Name)->limit(1)->get();
        if($Query->num_rows()  > 0){
            $Row = $Query->row_array();
            return $Row['u_id'];
        }else{
            return false;
        }
    }

    public function is_exist($V) {
        $Item = $this->_Item . __FUNCTION__;
        $Cache = $this->_Cache . __FUNCTION__ . $V;
        $Return = false;
        if (!($Return = $this->cache->get($Cache))) {
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)->from('usergroup')
                ->where('u_id', $V)->get();
            if ($Query->num_rows() > 0) {
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            } else {
                $GLOBALS['error'] = '没有符合搜索条件的用户组';
            }
        }
        return $Return;
    }

    public function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if($this->HostDb->insert('usergroup', $Data)){
            log_message('debug', "Model Usergroup_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Usergroup_model/insert Error");
            return false;
        }
    }

    public function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
	    $Data = $this->_format_re($Data, $Item);
		$this->HostDb->where('u_id', $Where);
		$this->HostDb->update('usergroup', $Data);
		$this->remove_cache($this->_Cache);
		return TRUE;
    }

    /**
     * 删除用户组
     * @param unknown $Where
     */
    public function delete($Where){
        if (is_array($Where)) {
            $this->HostDb->where_in('u_id', $Where);
        }else {
            $this->HostDb->where('u_id', $Where);
        }
        $this->HostDb->delete('usergroup');
        $this->remove_cache($this->_Module);
        return TRUE;
    }

    private function _get_role_tree(){
        $Rid = $this->session->userdata('rid');
        $Child = array();
        if(!!($Query = $this->select_role())){
            foreach ($Query as $key => $value){
                $Child[$value['r_parent']][] = $value['r_id'];
            }
            ksort($Child);
            $Child = gh_infinity_category($Child, $Rid);
        }
        return $Child;
    }
}
