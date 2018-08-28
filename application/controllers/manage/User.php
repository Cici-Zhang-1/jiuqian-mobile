<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2015-4-23
 * @author ZhangCC
 * @version
 * @description  
 */
class User extends MY_Controller{
    private $__Search = array(
        'paging' => 0,
        'usergroup_v' => ''
    );
	public function __construct(){
		parent::__construct();
        log_message('debug', 'Controller Manage/User Start!');
		$this->load->model('manage/user_model');
        $this->load->model('permission/usergroup_model');
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
	    $this->__Search['usergroup_v'] = implode(',', $this->__read_usergroup());
	    $this->_Search = array_merge($this->_Search, $this->__Search);
	    $this->get_page_search();
        $Data = array();
        if(!($Data = $this->user_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
	}

    /**
     * 获取子用户组
     * @return array|bool
     */
	private function __read_usergroup($Ugid = 0) {
	    $_Search = $this->_Search;
        $this->_Search['paging'] = 0;
        $this->get_page_search();
        if(!($Data = $this->usergroup_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
            $this->_Search = $_Search;
            return false;
        } else {
            foreach ($Data['content'] as $key => $value){
                $Child[$value['parent']][] = $value['v'];
            }
            ksort($Child);
            $Ugid = !empty($Ugid) ? $Ugid : $this->session->userdata('ugid');
            $Child = gh_infinity_category($Child, $Ugid);
            $this->_Search = $_Search;
            return $Child;
        }
    }
/*
	private function _infinity_category($Tree, $Parent = 0){
		$Return = array();
		if(is_array($Tree) && count($Tree) > 0 && isset($Tree[$Parent])){
			foreach ($Tree[$Parent] as $key => $value){
				$Return[] = $value['uid'];
				if(isset($Tree[$value['ugid']])){
					$Tmp = $this->_infinity_category($Tree, $value['ugid']);
					$Return = array_merge($Return, $Tmp);
				}
			}
		}
		return $Return;
	}*/

	public function add(){
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if (!!($Cid = $this->user_model->insert($Post))) {
                $this->_set_usergroup_amount($Post['usergroup_v']);
                $this->Message = '新建成功, 刷新后生效!';
            } else {
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
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
            $UsergroupV = $this->user_model->select_usergroup_v($Where);
            if(!!($this->user_model->update($Post, $Where))){
                if ($UsergroupV['usergroup_v'] != $Post['usergroup_v']) {
                    $this->_set_usergroup_amount($UsergroupV['usergroup_v']);
                    $this->_set_usergroup_amount($Post['usergroup_v']);
                }
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
	}
	public function start () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if (!!($this->user_model->update(array('status' => START_WORK), $Post))) {
                $this->Message = '启用成功, 刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'启用失败';
            }
        }
        $this->_ajax_return();
    }

    public function stop () {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if (!!($User = $this->user_model->work_status($Post))) {
                $this->load->library('arrange_work');
                foreach ($User as $Key => $Value) {
                    $this->arrange_work->stop($Value);
                }
                if(!!($this->user_model->update(array('status' => START_WORK), $Post))){
                    $this->Message = '停用成功, 刷新后生效!';
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'停用失败';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '用户不存在';
            }
        }
        $this->_ajax_return();
    }

    public function offtime () {

    }
	
	public function remove(){
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            $UsergroupVs = $this->user_model->select_usergroup($Where);
            if ($this->user_model->delete($Where)) {
                foreach ($UsergroupVs as $Key => $Value) {
                    $this->_set_usergroup_amount($Value['usergroup_v']);
                }
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
	}

    /**
     * 属主
     */
	public function owner () {
        $Data = array();
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('客服部'))) {
            $UsergroupVs = $this->__read_usergroup($UsergroupV);
            array_push($UsergroupVs, $UsergroupV);
            $this->__Search['usergroup_v'] = implode(',', $UsergroupVs);
            $this->_Search = array_merge($this->_Search, $this->__Search);
            $this->get_page_search();
            if(!($Data = $this->user_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            }
        } else {
            $this->Message = '请先设立客服部';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
    /**
     * 电子锯
     */
	public function electronic_saw() {
	    if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('电子锯'))) {
	        $this->__Search['usergroup_v'] = $UsergroupV;
            $this->_Search = array_merge($this->_Search, $this->__Search);
            $this->get_page_search();
            $Data = array();
            if(!($Data = $this->user_model->select($this->_Search))){
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
                $this->Code = EXIT_ERROR;
            } else {
                foreach ($Data['content'] as $Key => $Value) {
                    $Value['name'] = $Value['truename'];
                    $Data['content'][$Key] = $Value;
                }
            }
        } else {
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }

    public function edger () {
        $Data = array();
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('封边'))) {
            $Data = $this->_get_user($UsergroupV);
        } else {
            $this->Message = '请先建立封边用户组!';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
    public function puncher () {
        $Data = array();
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('打孔'))) {
            $Data = $this->_get_user($UsergroupV);
        } else {
            $this->Message = '请先建立打孔用户组!';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
    public function sscanner () {
        $Data = array();
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('扫描'))) {
            $Data = $this->_get_user($UsergroupV);
        } else {
            $this->Message = '请先建立扫描用户组!';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
    public function ppacker () {
        $Data = array();
        if (!!($UsergroupV = $this->usergroup_model->select_usergroup_id('打包'))) {
            $Data = $this->_get_user($UsergroupV);
        } else {
            $this->Message = '请先建立包装用户组!';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
    private function _get_user($UsergroupV) {
        $this->__Search['usergroup_v'] = $UsergroupV;
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->user_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            foreach ($Data['content'] as $Key => $Value) {
                $Value['name'] = $Value['truename'];
                $Data['content'][$Key] = $Value;
            }
        }
        return $Data;
    }

    private function _set_usergroup_amount ($UsergroupV) {
	    $this->load->model('permission/usergroup_model');
	    return $this->usergroup_model->update($this->user_model->select_usergroup_amount($UsergroupV), $UsergroupV);
    }
}
