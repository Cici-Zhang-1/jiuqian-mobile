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
class Role_func extends MY_Controller {
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller permission/Role_func Start!');
        $this->load->model('permission/role_func_model');
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
                if (!!($ParentRoleFunc = $this->role_func_model->select_by_usergroup_v($this->session->userdata('ugid')))) {
                    if (!!($RoleFunc = $this->role_func_model->select_by_role_v($Role['v']))) {
                        $Tmp = array();
                        foreach ($RoleFunc as $Key => $Value) {
                            array_push($Tmp, $Value['v']);
                        }
                        foreach ($ParentRoleFunc as $Key => $Value) {
                            if (in_array($Value['v'], $Tmp)) {
                                $Value['checked'] = 1;
                                $ParentRoleFunc[$Key] = $Value;
                            }
                        }
                    }
                    $Data['role_v'] = $Role['v'];
                    $Data['content'] = $ParentRoleFunc;
                    $Data['num'] = count($ParentRoleFunc);
                    $Data['p'] = ONE;
                    $Data['pn'] = ONE;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您无权设置角色功能权限, 请联系管理员';
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'角色不存在, 请联系管理员';
            }
        }else {
            $this->Code = EXIT_ERROR;
            $this->Message = '请选择需要设置的角色!';
        }
        $this->_ajax_return($Data);
    }

    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($this->role_func_model->delete_by_rid($Post['rid']))){
                if (isset($Post['fid'])) {
                    $Data = array();
                    foreach ($Post['fid'] as $key => $value) {
                        $Data[] = array(
                            'fid' => $value,
                            'rid' => $Post['rid']
                        );
                    }
                    $this->role_func_model->insert_batch($Data);
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

    /**
     * 删除
     */
    public function remove(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false && is_array($Where) && count($Where) > 0){
                if($this->role_func_model->delete($Where)){
                    $this->Success .= '用户组-角色删除成功, 刷新后生效!';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户组-角色删除失败';
                }
            }else{
                $this->Failue .= '没有可删除项!';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
