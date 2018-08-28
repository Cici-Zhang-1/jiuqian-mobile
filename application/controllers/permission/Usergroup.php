<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/12
 * Time: 10:51
 *
 * Desc: 用户组管理
 */
class Usergroup extends MY_Controller{
    private $__Search = array(
        'paging' => 0,
        'usergroup_v' => array()
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller permission/Usergroup Start!');
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
        array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        if(!($Data = $this->usergroup_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            $TmpSource = array();
            $TmpDes = array();
            foreach ($Data['content'] as $key => $value){
                $ClassAlien = '|';
                for ($I = 0; $I < $value['class']; $I++) {
                    $ClassAlien .= '---';
                }
                $value['class_alien'] = $ClassAlien;
                $TmpSource[$value['v']] = $value;
                $Child[$value['parent']][] = $value['v'];
            }
            ksort($Child);
            $Child = gh_infinity_category($Child, $this->session->userdata('ugid'));
            $TmpDes[] = $TmpSource[$this->session->userdata('ugid')];
            while(list($key, $value) = each($Child)){
                $TmpDes[] = $TmpSource[$value];
            }
            $Data['content'] = $TmpDes;
        }
        $this->_ajax_return($Data);
    }

    public function add(){
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if ($Parent = $this->usergroup_model->is_exist($Post['parent'])) {
                $Post['class'] = $Parent['class'] + 1;
            }
            if(!!($Cid = $this->usergroup_model->insert($Post))) {
                $this->Message = '新建成功, 刷新后生效!';
            }else{
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
            if ($Parent = $this->usergroup_model->is_exist($Post['parent'])) {
                $Post['class'] = $Parent['class'] + 1;
                if(!!($this->usergroup_model->update($Post, $Where))){
                    $this->Message = '内容修改成功, 刷新后生效!';
                }else{
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '请选择正确的父级用户组';
            }
        }
        $this->_ajax_return();
    }

    public function remove(){
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->usergroup_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
    /*
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false){
                $this->load->model('permission/usergroup_role_model');
                if(!!($this->usergroup_model->delete($Where)) && !!($this->usergroup_role_model->delete_by_uid($Where))){
                    $this->Success .= '用户组信息删除成功, 刷新后生效!';
                }else{
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户组信息删除失败';
                }
            }else{
                $this->Failue .= '没有可删除项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    } */
}
