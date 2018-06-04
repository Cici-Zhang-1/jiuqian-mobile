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


    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($Id = $this->usergroup_role_model->insert($Post))){
                $this->Success .= '用户组-角色新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户组-角色新增失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }

    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($this->usergroup_role_model->delete_by_uid($Post['uid']))){
                if (isset($Post['rid'])) {
                    $Data = array();
                    foreach ($Post['rid'] as $key => $value) {
                        $Data[] = array(
                            'rid' => $value,
                            'uid' => $Post['uid']
                        );
                    }
                    $this->usergroup_role_model->insert_batch($Data);
                }
                $this->Success .= '用户组-角色修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户组-角色修改失败';
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
                if($this->usergroup_role_model->delete($Where)){
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
