<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/12
 * Time: 10:50
 *
 * Desc: 用户角色
 */
class Role extends MY_Controller {
    private $_Module;
    private $_Controller;
    private $_Item ;

    public function __construct() {
        parent::__construct();
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

        $this->load->model('permission/role_model');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $this->data['action'] = site_url($Item);
            $this->load->view($Item, $this->data);
        }
    }

    public function read() {
        $this->_Item = $this->_Item.__FUNCTION__;
        $Data = array();
        if(!($Query = $this->role_model->select())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有用户角色信息';
        }else{
            $Data['content'] = $Query;
        }
        $this->_return($Data);
    }


    public function add(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($Id = $this->role_model->insert($Post))){
                $this->load->model('permission/usergroup_role_model');  // 在添加角色时，给管理组生成对应的角色
                $this->usergroup_role_model->insert(array('uid' => SUPER_NO, 'rid' => $Id));
                $this->Success .= '用户角色新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户角色新增失败';
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
            $Where = $this->input->post('selected');
            if(!!($this->role_model->update_role($Post, $Where))){
                $this->Success .= '用户角色修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户角色修改失败';
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
                if($this->role_model->delete($Where)){
                    $this->load->model('permission/role_menu_model');   // 删除角色时，要清除角色-菜单权限管理
                    $this->role_menu_model->delete_by_rid($Where);
                    $this->load->model('permission/role_func_model');   // 删除角色时，要清除角色-功能权限管理
                    $this->role_func_model->delete_by_rid($Where);
                    $this->load->model('permission/role_form_model');   // 删除角色时，要清除角色-表单权限管理
                    $this->role_form_model->delete_by_rid($Where);
                    $this->load->model('permission/role_card_model');   // 删除角色时，要清除角色-卡片权限管理
                    $this->role_card_model->delete_by_rid($Where);
                    $this->load->model('permission/role_element_model');   // 删除角色时，要清除角色-元素权限管理
                    $this->role_element_model->delete_by_rid($Where);
                    $this->Success .= '用户角色删除成功, 刷新后生效!';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'用户角色删除失败';
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
