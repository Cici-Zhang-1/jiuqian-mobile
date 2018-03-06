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
    }
	
    /**
     * 判断用户是否已经登录
     * 判断session和cookie
     * @access public
     * @return void
     */

	public function is_login(){
	    if(!$this->_is_sign_page()){ // 如果不是signpage就要判断用户登陆状态
			$Message = '';
	        if(is_null(self::$_sign_in)){
				$Uid = $this->_CI->input->cookie('uid') || $this->_CI->input->post('access2008_cookie_uid'); // Default or Upload
				if (empty($Uid)) {
					self::$_sign_in = false;
					$Message = '请登陆系统!';
				}else {
					if (!!($User = $this->_CI->user->signed_in($Uid))) {
					    log_message('debug', 'Sign In Success By ' . $Uid);
						self::$_sign_in = true;
					}else {
						self::$_sign_in = false;
						$Message = '您的会话已过期，请重新登陆系统!';
					}

				}
	        }

	        if(!self::$_sign_in){
				$Return = array(
					'code' => EXIT_SIGNIN,
					'message' => $Message
				);
				exit(json_encode($Return));
	        }
	    }
	}
	
	/**
	 * 判断是否是执行登录/登出操作
	 */
	private function _is_sign_page(){
	    return preg_match('/^sign\//', uri_string());
	}
}

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */
