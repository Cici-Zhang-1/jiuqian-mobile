<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/13
 * Time: 11:22
 *
 * Desc:
 */
class Role_visit extends MY_Controller {
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller permission/Role_visit Start!');
        $this->load->model('permission/role_visit_model');
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
                if (!!($ParentRoleCard = $this->role_visit_model->select_by_usergroup_v($this->session->userdata('ugid')))) {
                    if (!!($RoleCard = $this->role_visit_model->select_by_role_v($Role['v']))) {
                        $Tmp = array();
                        foreach ($RoleCard as $Key => $Value) {
                            array_push($Tmp, $Value['v']);
                        }
                        foreach ($ParentRoleCard as $Key => $Value) {
                            if (in_array($Value['v'], $Tmp)) {
                                $Value['checked'] = 1;
                                $ParentRoleCard[$Key] = $Value;
                            }
                        }
                    }
                    $Data['role_v'] = $Role['v'];
                    $Data['content'] = $ParentRoleCard;
                    $Data['num'] = count($ParentRoleCard);
                    $Data['p'] = ONE;
                    $Data['pn'] = ONE;
                } else {
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您无权设置角色访问权限, 请联系管理员';
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
            if(!!($this->role_visit_model->delete_by_rid($Post['rid']))){
                if (isset($Post['vid'])) {
                    $Data = array();
                    foreach ($Post['vid'] as $key => $value) {
                        $Data[] = array(
                            'vid' => $value,
                            'rid' => $Post['rid']
                        );
                    }
                    $this->role_visit_model->insert_batch($Data);
                }
                $this->Success .= '角色-访问控制权限修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'角色-访问控制修改失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
