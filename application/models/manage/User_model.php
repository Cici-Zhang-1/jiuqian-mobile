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
                $Query = $this->HostDb->select($Sql)->from('user AS U')
                    ->join('usergroup AS UG', 'UG.u_id = U.usergroup_id', 'left')
                    ->join('user AS UC', 'UC.u_id = U.u_id', 'left')->limit($Search['pagesize'], ($Search['p']-1)*$Search['pagesize'])->get();
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
