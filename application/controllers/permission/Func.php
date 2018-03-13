<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/13
 * Time: 09:35
 *
 * Desc: 功能管理
 */

class Func extends MY_Controller {
    private $_Module;
    private $_Controller;
    private $_Item ;

    public function __construct() {
        parent::__construct();
        $this->load->model('permission/func_model');
        $this->_Module = $this->router->directory;
        $this->_Controller = $this->router->class;
        $this->_Item = $this->_Module.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';

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

    private function _read() {
        $Id = $this->input->get('id', true);
        $Id = intval(trim($Id));

        if ($Id > 0) {
            $Data['Id'] = $Id;

            if (!!($Query = $this->func_model->select_by_mid($Id))) {
                $Data['content'] = $Query;
            }else {
                $Data['Error'] = '该菜单没有对应的功能项！';
            }
        }else {
            $Data['Error'] = '请选择需要设置角色的用户组!';
        }
        $this->load->view($this->_Item.__FUNCTION__, $Data);
    }

    public function add() {
        $Item = $this->_Item.__FUNCTION__;
        if ($this->form_validation->run($Item)) {
            $Post = gh_escape($_POST);
            $Post['img'] = $_POST['img'];
            if(!!($Fid = $this->func_model->insert($Post))){
                $this->load->model('permission/role_func_model');
                $this->role_func_model->insert(array('rid' => SUPER_NO, 'fid' => $Fid)); // 新建功能都是关联超级管理员
                $this->Success .= '功能添加成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'功能添加失败!';
            }
        }else {
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }

    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            $Post['img'] = $this->input->post('img');
            $Where = $Post['selected'];
            unset($Post['selected']);
            if(!!($this->func_model->update($Post, $Where))){
                $this->Success .= '功能修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'功能修改失败';
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
            if($Where !== false){
                $this->load->model('permission/role_func_model');
                if (!!($this->func_model->delete($Where)) && !!($this->role_func_model->delete_by_fid($Where))) {
                    $this->Success .= '功能删除成功，刷新后生效！';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'功能删除失败';
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
