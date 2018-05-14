<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian-mobile.
 * User: chuangchuangzhang
 * Date: 2018/3/13
 * Time: 11:11
 *
 * Desc:
 */
class Index extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $Data = array();
        $Data['truename'] = $this->session->userdata('truename');
        $Data = array_merge($Data, $this->_get_configs());
        if ($GLOBALS['MOBILE']) { // 加载移动首页
            $this->load->view('mobile/index');
        } else { // 加载Desk首页
            $Data['Menu'] = $this->_read_menu();
            $this->load->view('header', $Data);
            $this->load->view('index', $Data);
            $this->load->view('footer', $Data);
        }
    }

    /**
     * 获取menu
     * @return bool
     */
    private function _read_menu() {
        $Uid = $this->session->userdata('uid');
        $Uid = intval(trim($Uid));
        if($Uid){
            $this->load->model('permission/menu_model');
            if(!!($Menu = $this->menu_model->select_by_uid($Uid))){
                return $Menu;
            }else{
                gh_location('您没有开通权限，请联系管理员', site_url('sign/out'));
            }
        }else{
            gh_location('', site_url('sign/out'));
        }
    }

    public function page(){
        $this->load->view('page');
    }

    public function clear(){
        $this->load->helper('file');
        delete_cache_files('(.*)');
        $this->Success = '清除成功!';
        $this->_return();
    }
}
