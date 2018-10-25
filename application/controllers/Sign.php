<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2014-11-18
 * @author ZhangCC
 * @version
 * @description
 * 1. 用户会话结束后，自动销毁会话变量session，每次关闭浏览器后都需要再次登陆，这就要求cookie设置uid变量也是再关闭浏览器后自动销毁
 */
class Sign extends MY_Controller {
	private static $Refer = '';

	private $_Uri;
	public function __construct(){
		parent::__construct();
		log_message('debug','Controller Sign __construct');
	}
	public function index(){
	    $Item = $this->_Item.__FUNCTION__.'/';
	    $this->_Uri = explode('/', str_replace($Item, '', uri_string()));
	    if(count($this->_Uri) > 0){
	        $View = array_shift($this->_Uri);
	        $View = '_'.$View;
	        if(method_exists(__CLASS__, $View)){
	            $this->$View();
	        }else{
	            $this->load->view($View, $this->data);
	        }
	    }else{
	        show_404();
	    }
	}
	
	private function _in(){
	    if(isset($_SERVER['HTTP_REFERER'])){
	        $this->session->set_userdata('http_referer', $_SERVER['HTTP_REFERER']);
	        //$_SESSION['http_referer'] = $_SERVER['HTTP_REFERER'];
	    }else{
	        $this->session->set_userdata('http_referer', site_url());
	        //$_SESSION['http_referer'] = site_url();
	    }
        $Data = $this->_get_configs();
	    $Data['action'] = site_url('sign/in');
	    $this->load->view($this->_Item.__FUNCTION__,$Data);
	}

	public function in(){
	    $Data = array();
	    if ($this->_do_form_validation()) {
            $name = $this->input->post('name',true);
            $password = $this->input->post('password',true);
            $user = $this->user->check_login($name,$password);
            if($user){
                $this->load->model('manage/signin_model');
                $Set = array(
                    'user_id' => $user['uid'],
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'host' => ''
                );
                $this->signin_model->insert($Set);
                $this->_user_session($user);
                $Data = $this->_user_cookie($user);
                $this->Location = base_url('index.php');
            }else{
                $this->Code = EXIT_ERROR;
            }
        }
        $this->_return($Data);
	}

	public function out(){
		$this->load->helper('cookie');
		$this->session->sess_destroy();
		$this->load->helper('global_func');
		$CookieKeys = explode(' ', $this->config->item('cookie_keys'));
		foreach ($CookieKeys as $value){
			delete_cookie($value);
		}
		$this->Location = base_url('/sign/index/in');
		$this->_ajax_return();
		// Header("Location: ".base_url('/index.php/sign'));
		// exit();
	}
	
	/**
	 * 更新用户session信息
	 * @param unknown $User
	 */
	private function _user_session($User){
	    $SessionKeys = explode(' ', $this->config->item('session_keys'));
	    foreach ($SessionKeys as $value){
	        if (is_null($User[$value])) {
	            continue;
            } else {
                $Session[$value] = $User[$value];
            }
	    }
	    $this->session->set_userdata($Session);
	}
	
	/**
	 * 更新客户cookie信息
	 * @param unknown $User
	 */
	private function _user_cookie($User){
	    $CookieKeys = explode(' ', $this->config->item('cookie_keys'));
	    $Cookies = array();
	    foreach ($CookieKeys as $value){
	        if (is_null($User[$value])) {
	            continue;
            } else {
                $Cookie = array(
                    'name' => $value,
                    'value' => $User[$value],
                    'expire' => 0
                );
                array_push($Cookies, $Cookie);
                $this->input->set_cookie($Cookie);
            }
	    }
	    return $Cookies;
	}
}
