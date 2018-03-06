<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月4日
 * @author 记录用户登录信息模块
 * @version
 * @des
 */

class Signin_model extends Base_Model{
    private $_Module = 'manage';
    private $_Model;
    private $_Item;
    private $_Cache;
    private $_Num;

    public function __construct(){
        parent::__construct(false);
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Item;
        
        log_message('debug', 'Model Manage/Signin_model Start');
    }

    public function select(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->HostDb->select($Sql)
                                    ->from('user')
                                ->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array()
                );
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有用户!';
                $Return = false;
            }
        }
        return $Return;
    }

    function select_self($Con){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            if(empty($Con['pn'])){
                $Con['pn'] = $this->_page($Con);
            }else{
                $this->_Num = $Con['num'];
            }
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->HostDb->select($Sql)
                                        ->from('signin')
                                        ->where('s_user_id', $this->input->cookie('uid'))
                                        ->order_by('s_create_datetime', 'desc')
                                    ->get();
            if($Query->num_rows() > 0){
                $Return = array(
                    'content' => $Query->result_array(),
                    'num' => $this->_Num,
                    'p' => $Con['p'],
                    'pn' => $Con['pn']
                );
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }
        }
        return $Return;
    }

    private function _page($Con){
        $this->HostDb->select('count(s_id) as num', FALSE);
        $this->HostDb->from('signin');
        $this->HostDb->where('s_user_id', $this->input->cookie('uid'));
        $Query = $this->HostDb->get();
        if($Query->num_rows() > 0){
            $Row = $Query->row_array();
            $Query->free_result();
            $this->_Num = $Row['num'];
            if(intval($Row['num']%$Con['pagesize']) == 0){
                $Pn = intval($Row['num']/$Con['pagesize']);
            }else{
                $Pn = intval($Row['num']/$Con['pagesize'])+1;
            }
            log_message('debug', 'Num is '.$Row['num'].' and Pagesize is'.$Con['pagesize'].' and Page Nums is'.$Pn);
            return $Pn;
        }else{
            return false;
        }
    }

    /**
     * @param $Data
     * @return bool
     * @des 增加用户登录信息
     */
    function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item, $this->_Module);
        if($this->HostDb->insert('signin', $Data)){
            log_message('debug', "Model Signin_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->HostDb->insert_id();
        }else{
            log_message('debug', "Model Signin_model/insert Error");
            $GLOBALS['error'] = $this->HostDb->error();
            return false;
        }
    }
    
    function update($Data, $Where){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format_re($Data, $Item, $this->_Module);
        if(!empty($Data['u_password'])){
            $Data['u_salt'] = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            $Data['u_password'] = crypt($Data['u_password'], $Data['u_salt']);
        }
        $this->HostDb->where('u_id', $Where);
        $this->HostDb->update('user', $Data);
        $this->remove_cache($this->_Cache);
        $Error = $this->HostDb->error();
        if(empty($Error['code'])){
            return TRUE;
        }else{
            $GLOBALS['error'] = $Error['message'];
            return false;
        }
    }
    
    function check_reg($email){
        $query = $this->HostDb->get_where('f_user',array('email'=>$email));
        return $query->row_array();
    }
    function check_username($username){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$username;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->HostDb->select($Sql)
                        ->from('user as U')
                        ->join('usergroup as UG', 'UG.u_id = U.u_usergroup_id', 'left')
                        ->where('U.u_name', $username)
                        ->limit(1)->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, DAYS);
            }
        }
        return $Return;
    }
    /**
     * 检查用户登录状况
     * @param unknown $username
     * @param unknown $password
     * @return unknown|boolean
     */
    function check_login($username,$password){
        $query = $this->check_username($username);
        if(@$query['password']==crypt($password, $query['salt'])){
            $GLOBALS['uniqid'] = uniqid(mt_rand(),true);
            $Session_id = session_id();
            $this->HostDb->where('u_id', @$query['u_id'])->update('user',array('u_uniqid' =>$GLOBALS['uniqid'], 'u_session' => $Session_id));
            unset($query['password'], $query['salt']);
            return $query;
        } else {
            return false;
        }
    }
    public function get_user_by_id($uid)
    {
        $query = $this->HostDb->get_where('user', array('u_id'=>$uid));
        if($query){
            return $query->row_array();
        }
        return false;
    }

    /**
     * 根据uid判断是否为有效用户
     * @param unknown $uid
     */
    public function is_user($uid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$uid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $SessionId = session_id();
            $Query = $this->HostDb->select($Sql)->from('user as U')->join('usergroup as UG', 'UG.u_id = U.u_usergroup_id', 'left')
                ->where('U.u_id', $uid)->where('U.u_session', $SessionId)->limit(1)->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, DAYS);
            }
        }
        return $Return;
    }

    function update_user($data, $uid){
        $this->HostDb->where('u_id',$uid);
        $this->HostDb->update('user', $data);
        return ($this->HostDb->affected_rows() > 0) ? TRUE : FALSE;
    }
    function update_pwd($data){
        $this->HostDb->where('uid',$data['uid']);
        $this->HostDb->where('password',$data['password']);
        $this->HostDb->update('users', array('password'=>$data['newpassword']));
        return $this->HostDb->affected_rows();
    }
    function update_avatar($avatar,$uid)
    {
        $this->HostDb->where('uid',$uid);
        $this->HostDb->update('users', array('avatar'=>$avatar));
    }
    public function get_all_users($page, $limit)
    {
        $this->HostDb->select('*');
        $this->HostDb->from('users');
        $this->HostDb->order_by('uid','desc');
        $this->HostDb->limit($limit,$page);
        $query = $this->HostDb->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
    }
    public function get_users($limit,$ord)
    {
        $this->HostDb->select('uid,username,avatar');
        $this->HostDb->from('users');
        if($ord=='new'){
            $this->HostDb->order_by('uid','desc');
        }
        if($ord=='hot'){
            $this->HostDb->order_by('lastlogin','desc');
        }
        $this->HostDb->limit($limit);
        $query = $this->HostDb->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
    }

    function get_user_msg($uid,$username){
        if($uid){
            $query = $this->HostDb->select('username')->get_where('users',array('uid'=>$uid));
        }else{
            $query = $this->HostDb->select('uid')->get_where('users',array('username'=>$username));
        }
        return $query->row_array();
    }

    public function getpwd_by_username($username)
    {
        $query = $this->HostDb->select('uid,email,password,group_type')->get_where('users', array('username'=>$username));
        return $query->row_array();
    }
    public function get_user_by_username($username)
    {
        $query = $this->HostDb->limit(1)->get_where('users', array('username'=>$username));
        return $query->result_array();
    }

    public function userSelect(){
        $query = $this->HostDb->get('f_user');
        if($query->num_rows()>0){
            return $query->result_array();
        }else{
            return false;
        }
    }
    
    /**
     * 删除用户
     * @param unknown $Where
     */
    public function delete($Where){
        $this->HostDb->where_in('u_id', $Where);
        $this->HostDb->delete('user');
        $this->remove_cache($this->_Cache);
        return true;
    }
}