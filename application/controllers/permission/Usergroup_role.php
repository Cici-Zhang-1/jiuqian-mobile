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
    private $_Module;
    private $_Controller;
    private $_Item ;

    public function __construct() {
        parent::__construct();
        $this->load->model('permission/usergroup_role_model');
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
            $Parent = $this->usergroup_role_model->select_by_child($Id);
            if (!!($Parent = $this->usergroup_role_model->select_by_child($Id))) {
                if (!!($Query = $this->usergroup_role_model->select_by_uid($Id))) {
                    foreach ($Query as $key => $value) {
                        $Query[$key] = $value['rid'];
                    }
                }else {
                    $Query = array();
                }
                foreach ($Parent as $key => $value) {
                    if (in_array($value['rid'], $Query)) {
                        $Parent[$key]['checked'] = 1;
                    }else {
                        $Parent[$key]['checked'] = 0;
                    }
                }
                $Data['content'] = $Parent;
            }else {
                $Data['Error'] = '您无权设置用户组角色!';
            }
        }else {
            $Data['Error'] = '请选择需要设置角色的用户组!';
        }
        $this->load->view($this->_Item.__FUNCTION__, $Data);
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
