<?php
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 16/6/4
 * Time: 14:02
 * Description: 记录用户登录信息
 */
class Signin extends MY_Controller{
    private $__Search = array(
        'user_v' => 0
    );
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Manage/Signin Start!');
        $this->load->model('manage/signin_model');
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

    public function read(){
        $this->__Search['user_v'] = $this->session->userdata('uid');
        $this->_Search = array_merge($this->_Search, $this->__Search);
        $this->get_page_search();
        $Data = array();
        if(!($Data = $this->signin_model->select($this->_Search))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        }
        $this->_ajax_return($Data);
    }
}
