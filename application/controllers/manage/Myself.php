<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-22
 * @author ZhangCC
 * @version
 * @description  
 * 个人中心
 */
class Myself extends MY_Controller{
	private $_Module;
    private $_Controller;
    private $_Item ;
	public function __construct(){
		parent::__construct();
		$this->load->model('manage/user_model');
		$this->_Module = $this->router->directory;
		$this->_Controller = $this->router->class;
		$this->_Item = $this->_Module.$this->_Controller.'/';
		log_message('debug', 'Controller Manage/Myself __construct Start!');
	}
	
	public function index(){
		$View = $this->uri->segment(4, 'read');
		$View = '_'.$View;
		if(method_exists(__CLASS__, $View)){
			$this->$View();
		}else{
			$Item = $this->_Item.$View;
			$Data['action'] = site_url($Item);
			$this->load->view($Item, $Data);
		}
	}
	
	private function _read(){
	    $Item = $this->_Item.__FUNCTION__;
	    $Data = array();
	    if(!!($Data['self'] = $this->user_model->select_self($this->session->userdata('uid')))){
	        unset($Self);
	        $Data['action'] = site_url($this->_Item.'edit');
	        $this->load->view($Item, $Data);
	    }else{
	        redirect();
	    }
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
