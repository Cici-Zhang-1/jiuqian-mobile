<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian.
 * User: chuangchuangzhang
 * Date: 2018/2/9
 * Time: 18:24
 *
 * Desc: Apps
 */
class Apps extends MY_Controller {
    private $_Apps;

    private $_Ugid;
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller Apps Start!');
        $this->_Ugid = $this->session->userdata('ugid');
        $this->_Ugid = intval(trim($this->_Ugid));
    }

    /**
     * 获取所有App
     */
    public function read() {

        $Apps = array();
        if($this->_Ugid){
            $this->load->model('permission/menu_model');
            if(!!($this->_Apps = $this->menu_model->select_allowed_by_ugid($this->_Ugid, true))){
                $Apps = $this->_apps_format();
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = '对不起，无法获取您的导航信息，请联系管理员';
            }
        }else{
            $this->Code = EXIT_SIGNIN;
            $this->Message = '无法获取您的信息，请重新登陆';
        }
        /*header('Access-Control-Allow-Origin: http://localhost:8080/');
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
        header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,PATCH,OPTIONS');*/
        $this->_ajax_return($Apps);
    }

    /**
     * @return array
     */
    private function _apps_format(){
        $Return = array();
        $first_navigation = array();
        $second_navigation = array();
        if(!empty($this->_Apps)){
            foreach ($this->_Apps as $index => $row){
                if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $row['img'], $Matched)) {
                    $row['img'] = $Matched[1];
                }
                if ($row['type'] == 'form') {
                    $row['page_forms'] = $this->_page_form_format($row['mid']);
                } else {
                    $row['funcs'] = $this->_func_format($row['mid']);
                    $row['page_search'] = $this->_page_search_format($row['mid']);
                    $row['cards'] = $this->_card_format($row['mid']);
                }
                switch ($row['class']){
                    case '0':
                        $first_navigation[$row['label']] = $row;
                        break;
                    case '1':
                        $row['home'] = true;
                        $second_navigation[$row['parent']][$row['label']] = $row;
                        break;
                }
            }
            foreach ($second_navigation as $index1 => $row1){
                foreach ($first_navigation as $index0 => $row0){
                    if ($row0['mid'] == $index1) {
                        $first_navigation[$index0]['children'] = $row1;
                    }
                }
            }
            $Return = $first_navigation;
        }
        return $Return;
    }

    private function _page_form_format($Mid) {
        $this->load->model('permission/page_form_model');
        $Return = array();
        if (!!($Query = $this->page_form_model->select_allowed($this->_Ugid, $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['value'] = '';
                $Return[$Value['name']] = $Value;
            }
        }
        return $Return;
    }

    private function _func_format($Mid) {
        $this->load->model('permission/func_model');
        $Return = array();
        if (!!($Query = $this->func_model->select_allowed($this->_Ugid, $Mid))) {
            foreach ($Query as $Key => $Value) {
                if (preg_match('/^<i\s+class=\"(.*)\"><\/i>$/', $Value['img'], $Matched)) {
                    $Value['img'] = $Matched[1];
                }
                $Value['forms'] = $this->_form_format($Mid, $Value['fid']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;
    }

    private function _form_format($Mid, $Fid) {
        $this->load->model('permission/form_model');
        $Return = array();
        if (!!($Query = $this->form_model->select_allowed($this->_Ugid, $Mid, $Fid))) {
            foreach ($Query as $Key => $Value) {
                foreach ($Query as $Key => $Value) {
                    $Return[$Value['name']] = $Value;
                }
            }
        }
        return $Return;
    }

    private function _card_format($Mid) {
        $this->load->model('permission/card_model');
        $Return = array();
        if (!!($Query = $this->card_model->select_allowed($this->_Ugid, $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['data'] = array();
                $Value['elements'] = $this->_element_format($Mid, $Value['cid']);
                $Return[$Key] = $Value;
            }
        }
        return $Return;
    }

    private function _element_format($Mid, $Cid) {
        $this->load->model('permission/element_model');
        $Return = array();
        if (!!($Query = $this->element_model->select_allowed($this->_Ugid, $Mid, $Cid))) {
            foreach ($Query as $Key => $Value) {
                $Return[$Value['name']] = $Value;
            }
        }
        return $Return;
    }

    private function _page_search_format($Mid) {
        $this->load->model('permission/page_search_model');
        $Return = array();
        if (!!($Query = $this->page_search_model->select_allowed($this->_Ugid, $Mid))) {
            foreach ($Query as $Key => $Value) {
                $Value['value'] = '';
                $Return[$Value['name']] = $Value;
            }
        }
        return $Return;
    }
}
