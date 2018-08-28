<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * { title | title | replace({'_': ' '}) } Controller
 *
 * @package  CodeIgniter
 * @category Controller
 */
class Role_form_page extends MY_Controller {
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller  __construct Start!');
        $this->load->model('permission/role_form_page_model');
        $this->load->model('permission/role_model');
    }

    /**
    *
    * @return void
    */
    public function index() {
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read () {
        $V = $this->input->get('v', true);
        $V = intval(trim($V));

        $Data = array();
        if ($V > 0) {
            if (!!($Role = $this->role_model->is_exist($V))) {
                if (!!($Query = $this->role_form_page_model->select_by_role_v($Role['v']))) {
                    foreach ($Query as $Key => $Value) {
                        $Query[$Key]['checked'] = intval($Value['checked']);
                    }
                    $Data['query']['role_v'] = $Role['v'];
                    $Data['content'] = $Query;
                    $Data['num'] = count($Query);
                    $Data['p'] = ONE;
                    $Data['pn'] = ONE;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = '请先开通菜单权限!';
                }
                /*if (!!($ParentRoleFormPage = $this->role_form_page_model->select_by_usergroup_v($this->session->userdata('ugid')))) {
                    if (!!($RoleFormPage = $this->role_form_page_model->select_by_role_v($Role['v']))) {
                        $Tmp = array();
                        foreach ($RoleFormPage as $Key => $Value) {
                            array_push($Tmp, $Value['v']);
                        }
                        foreach ($ParentRoleFormPage as $Key => $Value) {
                            if (in_array($Value['v'], $Tmp)) {
                                $Value['checked'] = 1;
                                $ParentRoleFormPage[$Key] = $Value;
                            }
                        }
                    }
                    $Data['query']['role_v'] = $Role['v'];
                    $Data['content'] = $ParentRoleFormPage;
                    $Data['num'] = count($ParentRoleFormPage);
                    $Data['p'] = ONE;
                    $Data['pn'] = ONE;
                } else {
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您无权设置角色表单页面权限, 请联系管理员';
                }*/
            } else {
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'角色不存在, 请联系管理员';
            }
        }else {
            $this->Message = '请选择需要设置的角色!';
        }
        $this->_ajax_return($Data);
    }

    /**
    *
    * @return void
    */
    public function edit() {
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $this->role_form_page_model->delete_by_role_v($Post['role_v']);
            foreach ($Post['v'] as $Key => $Value) {
                $Data[] = array(
                    'form_page_v' => $Value,
                    'role_v' =>$Post['role_v']
                );
            }
            $this->role_form_page_model->insert_batch($Data);
            $this->Message = '内容修改成功, 刷新后生效!';
        }
        $this->_ajax_return();
    }
}
