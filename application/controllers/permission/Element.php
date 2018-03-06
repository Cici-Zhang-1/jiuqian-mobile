<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/13
 * Time: 09:35
 *
 * Desc: Element管理
 */

class Element extends CWDMS_Controller {
    private $_Module;
    private $_Controller;
    private $_Item ;

    public function __construct() {
        parent::__construct();
        $this->load->model('permission/element_model');
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

            if (!!($Query = $this->element_model->select_by_cid($Id))) {
                $Data['content'] = $Query;
            }else {
                $Data['Error'] = '该元素没有对应的元素项！';
            }
        }else {
            $Data['Error'] = '请选择需要设置元素的元素!';
        }
        $this->load->view($this->_Item.__FUNCTION__, $Data);
    }

    public function add() {
        $Item = $this->_Item.__FUNCTION__;
        if ($this->form_validation->run($Item)) {
            $Post = gh_escape($_POST);
            if(!!($Eid = $this->element_model->insert($Post))){
                $this->load->model('permission/role_element_model');
                $this->role_element_model->insert(array('rid' => SUPER_NO, 'eid' => $Eid)); // 新建元素都是关联超级管理员
                $this->Success .= '元素添加成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'元素添加失败!';
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
            $Where = $Post['selected'];
            unset($Post['selected']);
            if(!!($this->element_model->update($Post, $Where))){
                $this->Success .= '元素修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'元素修改失败';
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
                $this->load->model('permission/role_element_model');
                if (!!($this->element_model->delete($Where)) && !!($this->role_element_model->delete_by_eid($Where))) {
                    $this->Success .= '元素删除成功，刷新后生效！';
                }else {
                    $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'元素删除失败';
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
