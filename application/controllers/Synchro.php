<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/20
 * Time: 9:54
 */
class Synchro extends MY_Controller {
    public function __construct(){
        parent::__construct();
        log_message('debug','Controller Synchro __construct');
    }

    public function read () {
        $UserId = $this->session->userdata('user_id');
        $Data = array();
        if (!empty($UserId)) {
            $Data = array(
                array(
                    'name' => 'user_id',
                    'value' => $UserId,
                    'expire' => 0
                )
            );
        }
        $this->_ajax_return($Data);
    }
    public function add() {
        require_once APPPATH . 'third_party/eapp/login.php';
        $Data = array();
        if (!!($User = getUserId())) {
            $this->user->update($User, $this->session->userdata('uid'));
            $this->_user_session($User);
            $Data = $this->_user_cookie($User);
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '无法同步';
        }
        $this->_ajax_return($Data);
    }

    /**
     * 更新用户session信息
     * @param array $User
     */
    private function _user_session($User){
        $this->session->set_userdata(array(
            'user_id' => $User['user_id']
        ));
    }

    /**
     * 更新客户cookie信息
     * @param array $User
     */
    private function _user_cookie($User){
        $Cookies = array();
        $Cookie = array(
            'name' => 'user_id',
            'value' => $User['user_id'],
            'expire' => 0
        );
        array_push($Cookies, $Cookie);
        $this->input->set_cookie($Cookie);
        return $Cookies;
    }

    /**
     * 解除绑定用户
     */
    public function remove () {
        $User = array(
            'user_id' => ''
        );
        $this->user->update($User, $this->session->userdata('uid'));
        $this->_user_session($User);
        $this->_user_cookie($User);
        $this->_ajax_return();
    }
}