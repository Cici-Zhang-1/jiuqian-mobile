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
