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
    public function __construct(){
        parent::__construct(__DIR__, __CLASS__);
        log_message('debug', 'Model Manage/User_model Start');
    }

    /**
     * 检测用户名是否存在
     * @param $username
     * @return bool
     */
    function check_username($username){
        $Item = $this->_Item.__FUNCTION__;
        $Return = false;
        $Sql = $this->_unformat_as($Item);
        $Query = $this->HostDb->select($Sql)
            ->from('user as U')
            ->join('usergroup as UG', 'UG.u_id = U.u_usergroup_id', 'left')
            ->where('U.u_name', $username)
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
     * @param string $username
     * @param string $password
     * @return array|boolean
     */
    function check_login($username,$password){
        if (!!($query = $this->check_username($username))) {
            if($query['password'] == crypt($password, $query['salt'])){
                $GLOBALS['uniqid'] = uniqid(mt_rand(),true);
                $Session_id = session_id();
                $this->HostDb->where('u_id', $query['uid'])->update('user',array('u_uniqid' =>$GLOBALS['uniqid'], 'u_session' => $Session_id));
                unset($query['password'], $query['salt']);
                return $query;
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
        log_message('debug', 'Signed In Sql is ' . $Sql);
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
}
