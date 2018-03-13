<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/13
 * Time: 09:35
 *
 * Desc: 页面表单管理
 */

class Page_form extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('permission/page_form_model');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->Item.$View;
            $this->data['action'] = site_url($Item);
            $this->load->view($Item, $this->data);
        }
    }

    private function _read() {
        $Id = $this->input->get('id', true);
        $Id = intval(trim($Id));

        if ($Id > 0) {
            $Data['Id'] = $Id;

            if (!!($Query = $this->page_form_model->select_by_mid($Id))) {
                $Data['content'] = $Query;
            }else {
                $Data['Error'] = '该菜单没有对应的页面表单项！';
            }
        }else {
            $Data['Error'] = '请选择需要设置页面表单的菜单!';
        }
        $this->load->view($this->Item.__FUNCTION__, $Data);
    }

    public function add() {
        $Item = $this->Item.__FUNCTION__;
        if ($this->form_validation->run($Item)) {
            $Post = gh_escape($_POST);
            if(!!($Fid = $this->page_form_model->insert($Post))){
                $this->load->model('permission/role_page_form_model');
                $this->role_page_form_model->insert(array('rid' => SUPER_NO, 'pfid' => $Fid)); // 新建功能都是关联超级管理员
                $this->Success .= '页面表单添加成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'页面表单添加失败!';
            }
        }else {
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }

    public function edit(){
        $Item = $this->Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            $Where = $Post['selected'];
            unset($Post['selected']);
            if(!!($this->page_form_model->update($Post, $Where))){
                $this->Success .= '页面表单修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'页面表单修改失败';
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
        $Item = $this->Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Where = $this->input->post('selected', true);
            if($Where !== false){
                $this->load->model('permission/role_page_form_model');
                if (!!($this->page_form_model->delete($Where)) && !!($this->role_page_form_model->delete_by_pfid($Where))) {
                    $this->Success .= '页面表单删除成功，刷新后生效！';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'页面表单删除失败';
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
