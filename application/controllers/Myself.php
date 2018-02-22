<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian.
 * User: chuangchuangzhang
 * Date: 2018/2/9
 * Time: 15:41
 *
 * Desc:
 * 个人类
 */

class Myself extends MY_Controller {
    private $_Module;
    private $_Controller;
    private $_Item ;
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Manage/Myself __construct Start!');

        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
    }

    /**
     * 读取个人信息
     */
    public function read() {
        $Item = $this->_Item.__FUNCTION__;
        if (!!($Data = $this->user->select_self($this->input->cookie('uid')))) {
            $this->Code = EXIT_SUCCESS;
            $this->Message .= '我的信息获取成功!';
        }else {
            $this->Code = EXIT_SIGNIN;
        }
        $this->_ajax_return($Data);
    }

    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            unset($Post['selected']);
            $Password = $this->input->post('password', true);
            if(empty($Password)){
                unset($Post['password']);
            }
            $where = $this->input->post('selected');
            if(!!($this->user_model->update($Post, $where))){
                $this->Success .= '个人信息修改成功, 请重新登录';
                $this->Location = site_url('sign/out');
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'个人信息修改失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
