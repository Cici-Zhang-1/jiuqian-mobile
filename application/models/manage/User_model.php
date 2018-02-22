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
    private $_Module = '';
    private $_Model;
    private $_Item;
    private $_Cache;

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Model Manage/User_model Start');

        $this->_Module = current_directory(__FILE__);
        if ($this->_Module == 'models') {
            $this->_Module = '';
        }
        $this->_Model = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Model.'/';
        $this->_Cache = $this->_Item;

    }

    public function select(){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->db->select($Sql)
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
    public function select_by_usergroup($Ugids){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.implode(',', $Ugids);
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->db->select($Sql)
                                    ->from('user as U')
                                    ->join('usergroup as UG', 'UG.u_id = U.u_usergroup_id', 'left')
                                    ->join('user as C', 'C.u_id = U.u_creator','left')
                                    ->where_in('U.u_usergroup_id', $Ugids)
                                    ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->result_array();
                $Query->free_result();
                $this->cache->save($Cache, $Return, MONTHS);
            }else{
                $GLOBALS['error'] = '没有用户!';
                $Return = false;
            }
        }
        return $Return;
    }

    /**
     * 获取个人信息
     * @param $Uid
     * @return bool
     */
    function select_self($Uid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->db->select($Sql)
                                        ->from('user')
                                        ->where('u_id', $Uid)
                                    ->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, MONTHS);
            }else {
                $GLOBALS['error'] = '对不起没有您的信息，请重新登陆';
            }
        }
        return $Return;
    }
    
    function insert($Data){
        $Item = $this->_Item.__FUNCTION__;
        $Data = $this->_format($Data, $Item, $this->_Module);
        $Data['u_salt'] = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
        $Data['u_password'] = crypt($Data['u_password'], $Data['u_salt']);
        if($this->db->insert('user', $Data)){
            log_message('debug', "Model User_model/insert Success!");
            $this->remove_cache($this->_Cache);
            return $this->db->insert_id();
        }else{
            log_message('debug', "Model User_model/insert Error");
            $GLOBALS['error'] = $this->db->error();
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
        $this->db->where('u_id', $Where);
        $this->db->update('user', $Data);
        $this->remove_cache($this->_Cache);
        $Error = $this->db->error();
        if(empty($Error['code'])){
            return TRUE;
        }else{
            $GLOBALS['error'] = $Error['message'];
            return false;
        }
    }
    
    function check_reg($email){
        $query = $this->db->get_where('f_user',array('email'=>$email));
        return $query->row_array();
    }
    function check_username($username){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$username;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $Query = $this->db->select($Sql)
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
            $this->db->where('u_id', @$query['uid'])->update('user',array('u_uniqid' =>$GLOBALS['uniqid'], 'u_session' => $Session_id));
            unset($query['password'], $query['salt']);
            return $query;
        } else {
            return false;
        }
    }
    public function get_user_by_id($uid)
    {
        $query = $this->db->get_where('user', array('u_id'=>$uid));
        if($query){
            return $query->row_array();
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
        $Sql = $this->_unformat_as($Item, $this->_Module);
        $SessionId = session_id();
        $Query = $this->db->select($Sql)->from('user')
            ->where('u_id', $Uid)
            ->where('u_session', $SessionId)
            ->limit(1)
            ->get();
        log_message('error', 'aaa' . $Query->num_rows());
        if ($Query->num_rows() > 0) {
            $Return = true;
        }
        return $Return;
    }
    /**
     * 根据uid判断是否为有效用户
     * @param $uid
     */
    public function is_user($uid){
        $Item = $this->_Item.__FUNCTION__;
        $Cache = $this->_Cache.__FUNCTION__.$uid;
        $Return = false;
        if(!($Return = $this->cache->get($Cache))){
            $Sql = $this->_unformat_as($Item, $this->_Module);
            $SessionId = session_id();
            $Query = $this->db->select($Sql)->from('user as U')
                ->join('usergroup as UG', 'UG.u_id = U.u_usergroup_id', 'left')
                ->where('U.u_id', $uid)->where('U.u_session', $SessionId)->limit(1)->get();
            if($Query->num_rows() > 0){
                $Return = $Query->row_array();
                $this->cache->save($Cache, $Return, DAYS);
            }
        }
        return $Return;
    }

    function update_user($data, $uid){
        $this->db->where('u_id',$uid);
        $this->db->update('user', $data);
        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }
    function update_pwd($data){
        $this->db->where('uid',$data['uid']);
        $this->db->where('password',$data['password']);
        $this->db->update('users', array('password'=>$data['newpassword']));
        return $this->db->affected_rows();
    }
    function update_avatar($avatar,$uid)
    {
        $this->db->where('uid',$uid);
        $this->db->update('users', array('avatar'=>$avatar));
    }
    public function get_all_users($page, $limit)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->order_by('uid','desc');
        $this->db->limit($limit,$page);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
    }
    public function get_users($limit,$ord)
    {
        $this->db->select('uid,username,avatar');
        $this->db->from('users');
        if($ord=='new'){
            $this->db->order_by('uid','desc');
        }
        if($ord=='hot'){
            $this->db->order_by('lastlogin','desc');
        }
        $this->db->limit($limit);
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result_array();
        }
    }

    function get_user_msg($uid,$username){
        if($uid){
            $query = $this->db->select('username')->get_where('users',array('uid'=>$uid));
        }else{
            $query = $this->db->select('uid')->get_where('users',array('username'=>$username));
        }
        return $query->row_array();
    }

    public function getpwd_by_username($username)
    {
        $query = $this->db->select('uid,email,password,group_type')->get_where('users', array('username'=>$username));
        return $query->row_array();
    }
    public function get_user_by_username($username)
    {
        $query = $this->db->limit(1)->get_where('users', array('username'=>$username));
        return $query->result_array();
    }

    public function userSelect(){
        $query = $this->db->get('f_user');
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
        $this->db->where_in('u_id', $Where);
        $this->db->delete('user');
        $this->remove_cache($this->_Cache);
        return true;
    }
}
