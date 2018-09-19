<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-22
 * @author ZhangCC
 * @version
 * @description  
 * 个人中心
 */
class Myself extends MY_Controller{
	public function __construct(){
		parent::__construct();
        log_message('debug', 'Controller Manage/Myself __construct Start!');
		$this->load->model('manage/user_model');
	}
	
	public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
	}
	
	public function read(){
        $Data = array();
        if(!($Data = $this->user_model->select_self())){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
	}

	public function edit(){
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            $Password = $this->input->post('password', true);
            if(empty($Password)){
                unset($Post['password']);
            }
            if(!!($this->user_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
	}

    public function start () {
        if (!!($this->user_model->update(array('status' => START_WORK), $this->session->userdata('uid')))) {
            $this->Message = '启用成功, 刷新后生效!';
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'启用失败';
        }
        $this->_ajax_return();
    }

    public function stop () {
	    $Post = array(
	        $this->session->userdata('uid')
        );
        if (!!($User = $this->user_model->work_status($Post))) {
            $this->load->library('arrange_work');
            foreach ($User as $Key => $Value) {
                $this->arrange_work->stop($Value);
            }
            if(!!($this->user_model->update(array('status' => STOP_WORK), $Post))){
                $this->Message = '停用成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'停用失败';
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '用户不存在';
        }
        $this->_ajax_return();
    }

    public function offtime () {

    }
}
