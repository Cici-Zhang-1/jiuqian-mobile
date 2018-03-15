<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/18
 * Time: 14:34
 *
 * Desc:
 */
class Visit extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('permission/visit_model');
    }

    public function index() {
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
        if(!($Query = $this->visit_model->select())){
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有访问控制信息';
        }else{
            $Data['content'] = $Query;
        }
        $this->_return($Data);
    }

    public function add() {
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($Id = $this->visit_model->insert($Post))){
                $this->load->model('permission/role_visit_model');  // 在添加角色时，给管理组生成对应的角色
                $this->role_visit_model->insert(array('rid' => SUPER_NO, 'vid' => $Id));
                $this->Success .= '访问控制新增成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'访问控制新增失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    public function edit() {
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            $Where = $this->input->post('selected');
            if(!!($this->visit_model->update($Post, $Where))){
                $this->Success .= '访问控制修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'访问控制修改失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
    public function remove() {
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($this->visit_model->delete($Where)){
                $this->load->model('permission/role_visit_model');   // 删除访问控制时，要清除角色-访问控制权限管理
                $this->role_visit_model->delete_by_vid($Where);
                $this->Success .= '访问控制删除成功, 刷新后生效!';
            }else {
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'访问控制删除失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
