<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created for jiuqian.
 * User: chuangchuangzhang
 * Date: 2018/1/24
 * Time: 18:15
 *
 * Desc:
 */

class Home extends MY_Controller{
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Home Start !');
    }

    public function index(){
        if ($GLOBALS['MOBILE']) {
            $this->load->view('mobile/home'); // 加载移动页
        } else {
            $this->load->view('home'); // Load Desk Page
        }
    }
}
