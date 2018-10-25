<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月4日
 * @author
 * @version
 * @des
 *  用户管理模块
 */

class User_model extends MY_Model{
    private $_Num;
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model Manage/User_model Start');
    }

    function select($Search) {
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
                $this->HostDb->select($Sql)->from('user AS U')
                    ->join('usergroup AS UG', 'UG.u_id = U.u_usergroup_id', 'left')
                    ->join('user AS UC', 'UC.u_id = U.u_creator', 'left');
                if (isset($Search['usergroup_v']) && $Search['usergroup_v'] != '') {
                    $this->HostDb->where_in('U.u_usergroup_id', explode(',', $Search['usergroup_v']));
                }
                $Query = $this->HostDb->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])
                    ->order_by('U.u_usergroup_id')
                    ->order_by('U.u_group_no')
                    ->order_by('U.u_name')
                    ->get();
                if ($Query->num_rows() > 0) {
                    $Return = array(
                        'content' => $Query->result_array(),
                        'num' => $this->_Num,
                        'p' => $Search['p'],
                        'pn' => $Search['pn']
                    );
                    $this->cache->save($Cache, $Return, MONTHS);
                } else {
                    $GLOBALS['error'] = '没有符合搜索条件的用户';
                }
            }
        }
        return $Return;
    }
    private function _page_num($Search){
        $this->HostDb->select('count(u_id) as num', FALSE);
        $this->HostDb->from('user');
        if (isset($Search['usergroup_v']) && $Search['usergroup_v'] != '') {
            $this->HostDb->where_in('u_usergroup_id', explode(',', $Search['usergroup_v']));
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
     * 个人信息
     */
    public function select_self() {
        $V = $this->session->userdata('uid');
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__ . $V;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)
                ->from('user')
                ->join('user_status', 'us_name = u_status', 'left')
                ->where('u_id', $V)
                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    public function select_usergroup_v ($V) {
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__ . $V;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item);
            $Query = $this->HostDb->select($Sql)
                ->from('user')
                ->where('u_id', $V)
                ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }
    /**
     * 检测用户名是否存在
     * @param $name
     * @return bool
     */
    function check_name($name){
        $Item = $this->_Item.__FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('user as U')
            ->join('usergroup as UG', 'UG.u_id = U.u_usergroup_id', 'left')
            ->where('U.u_name', $name)
            ->limit(1)->get();
        if($Query->num_rows() > 0){
            $Return = $Query->row_array();
        } else {
            $GLOBALS['message'] = '用户名不存在!';
        }
        return $Return;
    }
    /**
     * 检查用户登录状况
     * @param string $name
     * @param string $password
     * @return array|boolean
     */
    function check_login($name,$password){
        if (!!($query = $this->check_name($name))) {
            if($query['password'] == crypt($password, $query['salt'])){
                if ($query['status'] == STOP_WORK) {
                    $GLOBALS['message'] = '账户已经停用!';
                } else {
                    $GLOBALS['uniqid'] = uniqid(mt_rand(),true);
                    $Session_id = session_id();
                    $this->HostDb->where('u_id', $query['uid'])->update('user',array('u_uniqid' =>$GLOBALS['uniqid'], 'u_session' => $Session_id));
                    unset($query['password'], $query['salt']);
                    return $query;
                }
            } else {
                $GLOBALS['message'] = '密码错误!';
            }
        }
        return false;
    }
    /**
     * Date => 2018/02/09
     * 判断用户是否已经登陆
     * @param $Uid
     * @return bool
     */
    public function signed_in($Uid) {
        $Item = $this->_Item.__FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $SessionId = session_id();
        $Query = $this->db->select($Sql)->from('user')
            ->where('u_id', $Uid)
            ->where('u_session', $SessionId)
            ->limit(1)
            ->get();
        log_message('debug', 'aaa' . $Query->num_rows());
        if ($Query->num_rows() > 0) {
            $Return = true;
        }
        return $Return;
    }

    public function is_exist($Uid) {
        $Item = $this->_Item.__FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('user AS U')
            ->join('usergroup AS UG', 'UG.u_id = U.u_usergroup_id', 'left')
            ->where('U.u_id', $Uid)
            ->limit(1)
            ->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->row_array();
        }
        return $Return;
    }
    public function work_status($Vs) {
        $Item = $this->_Item.__FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)->from('user AS U')
            ->join('usergroup AS UG', 'UG.u_id = U.u_usergroup_id', 'left')
            ->where_in('U.u_id', $Vs)
            ->get();
        if ($Query->num_rows() > 0) {
            $Return = $Query->result_array();
        }
        return $Return;
    }
    public function select_usergroup ($Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $this->HostDb->select($Sql)->from('user');
        if (is_array($Where)) {
            $this->HostDb->where_in('u_id', $Where);
        } else {
            $this->HostDb->where('u_id', $Where);
        }
        $this->HostDb->group_by('u_usergroup_id');
        $Query = $this->HostDb->get();
        if ($Query->num_rows() > 0) {
            if (is_array($Where)) {
                $Return = $Query->result_array();
            } else {
                $Return = $Query->row_array();
            }
        }
        return $Return;
    }

    /**
     * 同一用户组人员数量
     * @param $UsergroupV
     * @return mixed
     */
    public function select_usergroup_amount ($UsergroupV) {
        $Query = $this->HostDb->select('u_id')
            ->from('user')
            ->where('u_usergroup_id', $UsergroupV)
            ->get();
        return $Query->num_rows();
    }

    private function _select_max_group_no ($Ugid) {
        $Query = $this->HostDb->select_max('u_group_no')
            ->where('u_usergroup_id', $Ugid)
            ->get('user');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            return $Row['u_group_no'] + 1;
        }else{
            return 1;
        }
    }

    public function insert($Data) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item);
        if(!empty($Data['u_group_no'])){
            $this->_update_group_no($Data['u_group_no'], $Data['u_usergroup_id']);
        }else{
            $Data['u_group_no'] = $this->_select_max_group_no($Data['u_usergroup_id']);
        }
        $Data['u_salt'] = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
        $Data['u_password'] = crypt($Data['u_password'], $Data['u_salt']);
        if($this->HostDb->insert('user', $Data)){
            $this->remove_cache($this->_Module);
            return $this->HostDb->insert_id();
        } else {
            $GLOBALS['error'] = '新增用户数据失败!';
            return false;
        }
    }

    public function update($Data, $Where) {
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item);
        $Query = $this->HostDb->select('u_group_no')->where(array('u_id' => $Where))->get('user');
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            if (!empty($Data['u_group_no'])) {
                if($Row['u_group_no'] < $Data['u_group_no']){
                    $this->_update_group_no_min($Row['u_group_no'], $Data['u_group_no'], $Data['u_usergroup_id']);
                }elseif ($Row['u_group_no'] > $Data['u_group_no']){
                    $this->_update_group_no_plus($Data['u_group_no'], $Row['u_group_no'], $Data['u_usergroup_id']);
                }
            }
        }else{
            $GLOBALS['error'] = '您要修改的用户不存在';
            return false;
        }

        if(!empty($Data['u_password'])){
            $Data['u_salt'] = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            $Data['u_password'] = crypt($Data['u_password'], $Data['u_salt']);
        }
        if (is_array($Where)) {
            $this->HostDb->where_in('u_id', $Where);
        } else {
            $this->HostDb->where('u_id', $Where);
        }
        $this->HostDb->update('user', $Data);
        $this->remove_cache($this->_Module);
        return true;
    }

    private function _update_group_no_min($Min, $Max, $Ugid){
        $Query = $this->HostDb->query("UPDATE j_user SET u_group_no = u_group_no-1 where u_group_no > $Min && u_group_no <= $Max && u_usergroup_id = $Ugid");
        if($Query){
            return true;
        }else{
            return false;
        }
    }
    private function _update_group_no_plus($Min, $Max, $Ugid){
        $Query = $this->HostDb->query("UPDATE j_user SET u_group_no = u_group_no+1 where u_group_no >= $Min && u_group_no < $Max && u_usergroup_id = $Ugid");
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
    private function _update_group_no($GroupNo, $Ugid){
        $Query = $this->HostDb->query("UPDATE j_user SET u_group_no = u_group_no+1 where u_group_no >= $GroupNo && u_usergroup_id = $Ugid");
        if($Query){
            return true;
        } else {
            return false;
        }
    }

    private function _delete_group_no($GroupNo, $Ugid){
        $Query = $this->HostDb->query("UPDATE j_user SET u_group_no = u_group_no-1 where u_group_no > $GroupNo && u_usergroup_id = $Ugid");
        if($Query){
            return true;
        } else {
            return false;
        }
    }

    public function update_batch($Data) {
        $Item = $this->_Item.__FUNCTION__;
        foreach ($Data as $key => $value){
            $Data[$key] = $this->_format_re($value, $Item);
        }
        $this->HostDb->update_batch('user', $Data, 'u_id');
        $this->remove_cache($this->_Module);
        return true;
    }
    public function delete($Where) {
        if(is_array($Where)){
            foreach ($Where as $key => $value) {
                $Query = $this->HostDb->select('u_group_no, u_usergroup_id')->where(array('u_id' => $value))->get('user');
                if ($Query->num_rows() > 0) {
                    $Row = $Query->row_array();
                    $Query->free_result();
                    $this->_delete_group_no($Row['u_group_no'], $Row['u_usergroup_id']);
                }
            }
        }else{
            $Query = $this->HostDb->select('u_group_no, u_usergroup_id')->where(array('u_id' => $Where))->get('user');
            if ($Query->num_rows() > 0) {
                $Row = $Query->row_array();
                $Query->free_result();
                $this->_delete_group_no($Row['u_group_no'], $Row['u_usergroup_id']);
            }
        }
        if(is_array($Where)){
            $this->HostDb->where_in('u_id', $Where);
        } else {
            $this->HostDb->where('u_id', $Where);
        }
        $this->HostDb->delete('user');
        $this->remove_cache($this->_Module);
        return true;
    }
}
