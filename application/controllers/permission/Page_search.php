<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-desk.
 * User: chuangchuangzhang
 * Date: 2018/2/13
 * Time: 09:35
 *
 * Desc: 页面搜索管理
 */

class Page_search extends MY_Controller {
    private $__Search = array(
        'paging' => 0,
        'v' => 0
    );
    public function __construct() {
        parent::__construct();
        log_message('debug', 'Controller  __construct permission/Page_search Start!');
        $this->load->model('permission/page_search_model');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $this->_index($View);
        }
    }

    public function read() {
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->page_search_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $Data['query']['menu_v'] = $this->_Search['v'];
        $this->_ajax_return($Data);
    }

    public function add() {
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            if (empty($Post['placeholder']) || $Post['placeholder'] == '') {
                $Post['placeholder'] = $Post['label'];
            }
            if (empty($Post['ide']) || $Post['ide'] == '') {
                $Post['ide'] = name_to_id($Post['name']);
            }
            $Post['url'] = htmlspecialchars_decode($Post['url']);
            if(!!($Fid = $this->page_search_model->insert($Post))) {
                $this->load->model('permission/role_page_search_model');
                $this->role_page_search_model->insert(array('role_v' => SUPER_NO, 'page_search_v' => $Fid));
                $this->Message = '新建成功, 刷新后生效!';
            }else{
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'新建失败!';
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_ajax_return();
    }

    public function edit(){
        if ($this->_do_form_validation()) {
            $Post = gh_escape($_POST);
            $Where = $Post['v'];
            unset($Post['v']);
            $Post['url'] = htmlspecialchars_decode($Post['url']);
            if(!!($this->page_search_model->update($Post, $Where))){
                $this->Message = '内容修改成功, 刷新后生效!';
            }else{
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'内容修改失败';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 删除
     */
    public function remove(){
        $V = $this->input->post('v');
        if (!is_array($V)) {
            $_POST['v'] = explode(',', $V);
        }
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if ($this->page_search_model->delete($Where)) {
                $this->Message = '删除成功，刷新后生效!';
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'删除失败!';
            }
        }
        $this->_ajax_return();
    }
}
