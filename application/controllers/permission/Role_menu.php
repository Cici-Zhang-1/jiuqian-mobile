<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/12
 * Time: 10:51
 *
 * Desc: 角色-菜单绑定
 */
class Role_menu extends MY_Controller {
    private $_Module;
    private $_Controller;
    private $_Item ;

    public function __construct() {
        parent::__construct();
        $this->load->model('permission/role_menu_model');
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
            $this->load->model('permission/menu_model');
            if (!!($Menu = $this->menu_model->select_menu())) {
                if (!!($Query = $this->role_menu_model->select_by_rid($Id))) {
                    foreach ($Query as $key => $value) {
                        $Query[$key] = $value['mid'];
                    }
                }else {
                    $Query = array();
                }
                foreach ($Menu as $key => $value) {
                    if (in_array($value['mid'], $Query)) {
                        $Menu[$key]['checked'] = 1;
                    }else {
                        $Menu[$key]['checked'] = 0;
                    }
                }
                $Data['content'] = $this->_format_menu($Menu);
            }else {
                $Data['Error'] = '您无权设置用户组角色!';
            }
        }else {
            $Data['Error'] = '请选择需要设置角色的用户组!';
        }
        $this->load->view($this->_Item.__FUNCTION__, $Data);
    }

    /**
     * 格式化Menu
     * @param $Menu
     * @param int $Pid
     * @return array
     */
    private function _format_menu($Menu, $Pid = 0) {
        $Data = array();
        foreach ($Menu as $key => $value){
            $Tmp[$value['mid']] = $value;
            $Child[$value['parent']][] = $value['mid'];
        }
        ksort($Child);
        $Child = gh_infinity_category($Child, $Pid);
        while(list($key, $value) = each($Child)){
            $Data[] = $Tmp[$value];
        }
        return $Data;
    }

    public function edit(){
        $Item = $this->_Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($this->role_menu_model->delete_by_rid($Post['rid']))){
                if (isset($Post['mid'])) {
                    $Data = array();
                    foreach ($Post['mid'] as $key => $value) {
                        $Data[] = array(
                            'mid' => $value,
                            'rid' => $Post['rid']
                        );
                    }
                    $this->role_menu_model->insert_batch($Data);
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
                if($this->role_menu_model->delete($Where)){
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
