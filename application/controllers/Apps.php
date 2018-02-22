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
    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取所有App
     */
    public function read() {
        $Ugid = $this->_CI->session->userdata('ugid');
        $Ugid = intval(trim($Ugid));
        $Apps = array();
        if($Ugid){
            $this->_CI->load->model('manage/usergroup_priviledge_model');
            if(!!($Apps = $this->_CI->usergroup_priviledge_model->select_apps($Ugid))){
                $Apps = $this->_nav_format($Apps);
            }else{
                $this->Code = EXIT_SIGNIN;
            }
        }else{
            $this->Code = EXIT_SIGNIN;
            $this->Message = '请先登陆';
        }
        $this->_ajax_return($Apps);
    }

    /**
     * @param $param1
     * @return string
     * database: {
    name: '成品库',
    font: 'fa fa-database',
    size: '',
    children: {
    location: {
    name: '库位',
    font: 'fa fa-align-justify',
    size: 'fa-2x',
    href: '/app/location',
    home: true, // 是否显示主页功能区
    funcs: [
    {
    name: '删除库位',
    font: 'fa fa-trash-o',
    href: '/funcs/location/remove'
    }
    ],
    data: {
    name: '库位',
    home: true, // 是否显示在home页card区
    type: 'table', // 数据展现的类型
    settings: true,
    keyword: '',
    length: 0,
    pagesize: 0,
    page: 1,
    uri: '/location/read',
    contents: []
    }
    }
    }
    }
     */
    private function _nav_format($param1){
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
                                $tmp = '<li><a href="'.$row['url'].'" name="'.$row['name'].'" title="'.$row['name'].'"><div class="common-font-24">'.$row['img'].'</div><span class="sider-text">'.$row['name'].'</span></a><ul class="sub-sidebar-form">';
                            }
                        }else{
                            if(empty($row['img'])){
                                $tmp = '<li ><a href="'.site_url($row['url']).'" name="'.$row['name'].'" title="'.$row['name'].'"><div class="common-font-24"><i class="fa fa-file"></i></div><span class="sider-text">'.$row['name'].'</span></a></li>';
                            }else{
                                $tmp = '<li ><a href="'.site_url($row['url']).'" name="'.$row['name'].'" title="'.$row['name'].'"><div class="common-font-24">'.$row['img'].'</div><span class="sider-text">'.$row['name'].'</span></a></li>';
                            }
                        }
                        $first_navigation[$row['id']] = $tmp;
                        break;
                    case '1':
                        if(empty($row['img'])){
                            $tmp = '<li><a href="'.site_url($row['url']).'" name="'.$row['name'].'" title="'.$row['name'].'" class="corner-all"><i class="fa fa-file"></i>&nbsp;&nbsp;<span class="sidebar-text">'.$row['name'].'</span></a></li><li class="divider"></li>';
                        }else{
                            $tmp = '<li><a href="'.site_url($row['url']).'" name="'.$row['name'].'" title="'.$row['name'].'" class="corner-all">'.$row['img'].'&nbsp;&nbsp;<span class="sidebar-text">'.$row['name'].'</span></a></li><li class="divider"></li>';
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
}
