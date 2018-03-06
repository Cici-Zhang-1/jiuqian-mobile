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
class Role_page_search extends CWDMS_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('permission/role_page_search_model');
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
            $this->load->model('permission/page_search_model');
            if (!!($PageSearch = $this->page_search_model->select())) {
                if (!!($Query = $this->role_page_search_model->select_by_rid($Id))) {
                    foreach ($Query as $key => $value) {
                        $Query[$key] = $value['psid'];
                    }
                }else {
                    $Query = array();
                }
                foreach ($PageSearch as $key => $value) {
                    if (in_array($value['psid'], $Query)) {
                        $PageSearch[$key]['checked'] = 1;
                    }else {
                        $PageSearch[$key]['checked'] = 0;
                    }
                }
                $Data['content'] = $PageSearch;
            }else {
                $Data['Error'] = '您无权设置角色页面搜索功能!';
            }
        }else {
            $Data['Error'] = '请选择需要设置页面搜索的角色!';
        }
        $this->load->view($this->Item.__FUNCTION__, $Data);
    }

    public function edit(){
        $Item = $this->Item.__FUNCTION__;
        if($this->form_validation->run($Item)){
            $Post = gh_escape($_POST);
            if(!!($this->role_page_search_model->delete_by_rid($Post['rid']))){
                if (isset($Post['psid'])) {
                    $Data = array();
                    foreach ($Post['psid'] as $key => $value) {
                        $Data[] = array(
                            'psid' => $value,
                            'rid' => $Post['rid']
                        );
                    }
                    $this->role_page_search_model->insert_batch($Data);
                }
                $this->Success .= '角色-页面搜索权限修改成功, 刷新后生效!';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'角色-页面搜索修改失败';
            }
        }else{
            $this->Failue .= validation_errors();
        }
        $this->_return();
    }
}
