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
        $Data = array_merge($Data, $this->_read_configs());
        if ($GLOBALS['MOBILE']) { // 加载移动首页
            $this->load->view('mobile/index');
        } else { // 加载Desk首页
            $Data['Menu'] = $this->_read_menu();
            $this->load->view('header', $Data);
            $this->load->view('index', $Data);
            $this->load->view('footer', $Data);
        }
    }

    private function _read_configs() {
        $this->load->model('manage/configs_model');
        $Configs = array();
        if (!!($Model = $this->configs_model->select())) {
            foreach ($Model as $Key => $Value) {
                $Configs[$Value['name']] = $Value['config'];
            }
        }
        return $Configs;
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

    private function _nav_format($Menu) {
        $Return = array();
        $Panel = '';
        $first_navigation = array();
        $second_navigation = array();
        $third_navigation = array();
        if(!empty($param1)){
            foreach ($param1 as $index => $row){
                switch ($row['class']){
                    case '0':
                        if($row['url'] == 'javascript:void(0);'){
                            if(empty($row['img'])){
                                $tmp = '<li><a href="'.$row['url'].'" name="'.$row['name'].'" title="'.$row['name'].'"><div class="common-font-24"><i class="fa fa-file"></i></div><span class="sider-text">'.$row['name'].'</span></a><ul class="sub-sidebar-form">';
                            }else{
                                if (preg_match('/^<i .*><\/i>$/', $row['img'])) {
                                    $tmp = '<li><a href="'.$row['url'].'" name="'.$row['name'].'" title="'.$row['name'].'"><div class="common-font-24">'.$row['img'].'</div><span class="sider-text">'.$row['name'].'</span></a><ul class="sub-sidebar-form">';
                                }else {
                                    $tmp = '<li><a href="'.$row['url'].'" name="'.$row['name'].'" title="'.$row['name'].'"><div class="common-font-24"><i class="'.$row['img'].'"></i></div><span class="sider-text">'.$row['name'].'</span></a><ul class="sub-sidebar-form">';
                                }
                            }
                        }else{
                            if(empty($row['img'])){
                                $tmp = '<li ><a href="'.site_url($row['url']).'" name="'.$row['name'].'" title="'.$row['name'].'"><div class="common-font-24"><i class="fa fa-file"></i></div><span class="sider-text">'.$row['name'].'</span></a></li>';
                            }else{
                                if (preg_match('/^<i .*><\/i>$/', $row['img'])) {
                                    $tmp = '<li ><a href="'.site_url($row['url']).'" name="'.$row['name'].'" title="'.$row['name'].'"><div class="common-font-24">'.$row['img'].'</div><span class="sider-text">'.$row['name'].'</span></a></li>';
                                }else {
                                    $tmp = '<li ><a href="'.site_url($row['url']).'" name="'.$row['name'].'" title="'.$row['name'].'"><div class="common-font-24"><i class="'.$row['img'].'"></i></div><span class="sider-text">'.$row['name'].'</span></a></li>';
                                }

                            }
                        }
                        $first_navigation[$row['id']] = $tmp;
                        break;
                    case '1':
                        if(empty($row['img'])){
                            $tmp = '<li><a href="'.site_url($row['url']).'" name="'.$row['name'].'" title="'.$row['name'].'" class="corner-all"><i class="fa fa-file"></i>&nbsp;&nbsp;<span class="sidebar-text">'.$row['name'].'</span></a></li><li class="divider"></li>';
                        }else{
                            if (preg_match('/^<i .*><\/i>$/', $row['img'])) {
                                $tmp = '<li><a href="'.site_url($row['url']).'" name="'.$row['name'].'" title="'.$row['name'].'" class="corner-all">'.$row['img'].'&nbsp;&nbsp;<span class="sidebar-text">'.$row['name'].'</span></a></li><li class="divider"></li>';
                            }else {
                                $tmp = '<li><a href="'.site_url($row['url']).'" name="'.$row['name'].'" title="'.$row['name'].'" class="corner-all"><i class="'.$row['img'].'"></i>&nbsp;&nbsp;<span class="sidebar-text">'.$row['name'].'</span></a></li><li class="divider"></li>';
                            }
                        }
                        $second_navigation[$row['parent']][$row['id']] = $tmp;
                        break;
                }
            }
            foreach ($second_navigation as $index1 => $row1){
                $sn_tmp = implode('', $row1);
                foreach ($first_navigation as $index0 => $row0){
                    if ($index1 == $index0){
                        $first_navigation[$index0] .= $sn_tmp;
                        $first_navigation[$index0] .= '</ul></li>';
                    }
                }
            }
            $return = implode('', $first_navigation);
        }
        return $return ;
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
