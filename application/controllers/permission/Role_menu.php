<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/12
 * Time: 10:51
 *
 * Desc: 角色-菜单绑定
 */
class Role_menu extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('permission/role_menu_model');
        $this->load->model('permission/role_model');
        $this->load->model('permission/usergroup_role_model');
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

    public function read() {
        $V = $this->input->get('v', true);
        $V = intval(trim($V));

        $Data = array();
        if ($V > 0) {
            if (!!($Role = $this->role_model->is_exist($V))) {
                if (!!($ParentRoleMenu = $this->role_menu_model->select_by_usergroup_v($this->session->userdata('ugid')))) {
                    if (!!($RoleMenu = $this->role_menu_model->select_by_role_v($Role['v']))) {
                        $Tmp = array();
                        foreach ($RoleMenu as $Key => $Value) {
                            array_push($Tmp, $Value['v']);
                        }
                        foreach ($ParentRoleMenu as $Key => $Value) {
                            if (in_array($Value['v'], $Tmp)) {
                                $Value['checked'] = 1;
                                $ParentRoleMenu[$Key] = $Value;
                            }
                        }
                    }
                    $TmpSource = array();
                    $DesSource = array();
                    $Child = array();
                    foreach ($ParentRoleMenu as $key => $value){
                        $ClassAlien = '|';
                        for ($I = 0; $I < $value['class']; $I++) {
                            $ClassAlien .= '---';
                        }
                        $value['class_alien'] = $ClassAlien;
                        $TmpSource[$value['v']] = $value;
                        $Child[$value['parent']][] = $value['v'];
                    }
                    ksort($Child);
                    $Child = gh_infinity_category($Child);
                    while(list($key, $value) = each($Child)){
                        $DesSource[] = $TmpSource[$value];
                    }
                    $Data['role_v'] = $Role['v'];
                    $Data['content'] = $DesSource;
                    $Data['num'] = count($DesSource);
                    $Data['p'] = ONE;
                    $Data['pn'] = ONE;
                } else {
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您无权设置角色菜单权限, 请联系管理员';
                }
            } else {
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'角色不存在, 请联系管理员';
            }
        }else {
            $this->Message = '请选择需要设置的角色!';
        }
        $this->_ajax_return($Data);
    }

    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($this->role_menu_model->delete_by_rid($Post['rid']))){
                if (isset($Post['mid'])) {
                    $Data = array();
                    foreach ($Post['mid'] as $key => $value) {
                        $Data[] = array(
                            'mid' => $value,
                            'rid' => $Post['rid']
                        );
                    }
                    $this->role_menu_model->insert_batch($Data);
                }
                $this->Success .= '角色-菜单权限修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'角色-菜单修改失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
