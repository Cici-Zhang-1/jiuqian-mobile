<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/*
 */
class Auth {
	/**
     * 用户
     *
     * @access private
     * @var array
     */
	private static $_sign_in = NULL;
	/**
    * CI句柄
    * 
    * @access private
    * @var object
    */
	private $_CI;

	 /**
	 * 构造函数
	 *
	 * @access public
	 * @return void
	 */
    public function __construct() {
		log_message('debug', "Auth Hook Start");

        /** 获取CI句柄 */
		$this->_CI = & get_instance();
        header("Access-Control-Allow-Origin: http://localhost:8080 ");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept ");
        header("Access-Control-Allow-Methods: GET, POST ");
    }
	
    /**
     * 判断用户是否已经登录
     * 判断session和cookie
     * @access public
     * @return void
     */

	public function is_signed_in(){
	    if(!$this->_is_sign_page()){ // 如果不是signpage就要判断用户登陆状态
	        // if (!($this->_Dd_User = $this->_is_dd_code())) {
                $Message = '';
                if(is_null(self::$_sign_in)){
                    $Uid = $this->_CI->input->cookie('uid');
                    $Uid = $Uid ? $Uid : $this->_CI->input->post('access2008_cookie_uid'); // Default or Upload
                    if (empty($Uid)) {
                        self::$_sign_in = false;
                        $Message = '请登陆系统!';
                    }else {
                        log_message('debug', 'Sign In User Id By ' . $this->_CI->input->cookie('uid'));
                        if (!!($User = $this->_CI->user->signed_in($Uid))) {
                            self::$_sign_in = true;
                            $this->_global_session_keys();
                        }else {
                            self::$_sign_in = false;
                            $Message = '您的会话已过期，请重新登陆系统!';
                        }

                    }
                }

                if(!self::$_sign_in){
                    // gh_location('',site_url('sign/index/in'));
                    $Return = array(
                        'code' => EXIT_SIGNIN,
                        'message' => $Message
                    );
                    /*if ($GLOBALS['MOBILE']) {*/
                    if (isset($_GET['callback'])) {
                        exit($_GET['callback'] . '(' .json_encode($Return) . ')');
                    } else {
                        exit(json_encode($Return));
                    }
                    /*} else {
                        gh_location($Return['message'],site_url('sign/index/in'));
                    }*/
                }
//            } else {
//
//            }
	    }
	}
	
	/**
	 * 判断是否是执行登录/登出操作
	 */
	private function _is_sign_page(){
	    // return preg_match('/^sign\/|^generate/', uri_string());
	    return preg_match('/^sign|generate|home$/', $this->_CI->router->class);
	}

    /**
     * global session session_keys
     * @return bool
     */
	private function _global_session_keys() {
        $SessionKeys = explode(' ', $this->_CI->config->item('session_keys'));
        foreach ($SessionKeys as $SessionKey) {
            $GLOBALS[$SessionKey] = $this->_CI->session->userdata($SessionKey);

        }
        return true;
    }

    private function _is_dd_code () {
        require_once APPPATH . 'third_party/eapp/login.php';
        return getUserId();
    }
}

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */
