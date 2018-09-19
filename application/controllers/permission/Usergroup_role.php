<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/12
 * Time: 10:51
 *
 * Desc: 用户组-角色绑定
 */
class Usergroup_role extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('permission/usergroup_model');
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
            if (!!($Usergroup = $this->usergroup_model->is_exist($V))) {
                if ($Usergroup['v'] == $this->session->userdata('ugid')) {
                    $Usergroup['parent'] = $Usergroup['v'];
                }
                if (!!($ParentUsergroupRole = $this->usergroup_role_model->select_by_usergroup_v($Usergroup['parent']))) {
                    if (!!($MyselfUsergroupRole = $this->usergroup_role_model->select_by_usergroup_v($Usergroup['v']))) {
                        $Tmp = array();
                        foreach ($MyselfUsergroupRole as $Key => $Value) {
                            array_push($Tmp, $Value['v']);
                        }
                        foreach ($ParentUsergroupRole as $Key => $Value) {
                            if (in_array($Value['v'], $Tmp)) {
                                $Value['checked'] = 1;
                                $ParentUsergroupRole[$Key] = $Value;
                            }
                        }
                    }
                    $Data['query']['usergroup_v'] = $Usergroup['v'];
                    $Data['content'] = $ParentUsergroupRole;
                    $Data['num'] = count($ParentUsergroupRole);
                    $Data['p'] = ONE;
                    $Data['pn'] = ONE;
                } else {
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您无权设置用户组角色, 请联系管理员';
                }
            } else {
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户组不存在, 请联系管理员';
            }
        }else {
            $this->Message = '请选择需要设置角色的用户组!';
        }
        $this->_ajax_return($Data);
    }

    public function edit(){
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $this->usergroup_role_model->delete_by_usergroup_v($Post['usergroup_v']);
            foreach ($Post['v'] as $Key => $Value) {
                $Data[] = array(
                    'usergroup_v' => $Post['usergroup_v'],
                    'role_v' => $Value
                );
            }
            $this->usergroup_role_model->insert_batch($Data);
            $this->Message = '内容修改成功, 刷新后生效!';
        }
        $this->_ajax_return();
    }
}
