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
    private $_Module;
    private $_Controller;
    private $_Item ;

    public function __construct() {
        parent::__construct();
        $this->load->model('permission/role_func_model');
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
            $this->load->model('permission/func_model');
            if (!!($Func = $this->func_model->select())) {
                if (!!($Query = $this->role_func_model->select_by_rid($Id))) {
                    foreach ($Query as $key => $value) {
                        $Query[$key] = $value['fid'];
                    }
                }else {
                    $Query = array();
                }
                foreach ($Func as $key => $value) {
                    if (in_array($value['fid'], $Query)) {
                        $Func[$key]['checked'] = 1;
                    }else {
                        $Func[$key]['checked'] = 0;
                    }
                }
                $Data['content'] = $Func;
            }else {
                $Data['Error'] = '您无权设置角色功能!';
            }
        }else {
            $Data['Error'] = '请选择需要设置功能的角色!';
        }
        $this->load->view($this->_Item.__FUNCTION__, $Data);
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
