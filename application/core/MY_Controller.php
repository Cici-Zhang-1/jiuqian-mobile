<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2014-8-15
 * @author ZhangCC
 * @version
 * @description  
 */
class MY_Controller extends CI_Controller
{
	protected $Code = EXIT_SUCCESS;	// 返回码，默认是成功返回
	protected $Message = ''; // 返回时，携带的信息
	protected $Location = '';
	public function __construct()
	{
		parent::__construct();
		log_message('debug','Controller CWDMS_Controller/__construct Start');
	}
	/**
	 * @param array $Data
	 */
	public function _ajax_return($Data = array()) {
		$this->Message .= isset($GLOBALS['message'])?(is_array($GLOBALS['message'])?implode(',', $GLOBALS['message']):$GLOBALS['message']):'';
		exit(json_encode(array('code'=>$this->Code, 'message'=> $this->Message, 'contents' => $Data)));
	}

	/**
	 * 获得收索分页条件
	 * @param unknown $Cookie
	 * @param unknown $Con
	 */
	protected function get_page_conditions($Cookie, $Con){
	    $Return = array();
	    $Flag = false;
	    foreach ($Con as $key=>$value){
	        $Return[$key] = $this->input->get($key, true);
	        $Return[$key] = trim($Return[$key]);
	        if(false === $Return[$key] || '' == $Return[$key]){
	            $Return[$key] = $value;
	        }else{
	            $Flag = true;
	        }
	    }
	    if(!$Flag){
	        $P = $this->input->get('p', true);
	        $Page = $this->input->get('page', true);  //缓存页面
	        $Table = $this->input->get('table', true);  //缓存表格
	        $P = intval(trim($P));
	        if($P){
	            if((!!($Cookies = $this->input->cookie($Cookie, true))
	                   && !!($Condition = json_decode($Cookies, true)))
	                   || ($Page != false && !!($Cookies = $this->input->cookie($Page, true))
	                    && !!($Cookies = json_decode($Cookies, true))
	                    && isset($Cookies[$Table])
	                    && !!($Condition = $Cookies[$Table]))){
	                unset($Cookies);
	                $Return = array_merge($Return, $Condition);
	            }else{
	                $Return = $Con;
	            }
	            $Return['p'] = $P;
	            $Flag = true;
	        }
	    }
	    if(empty($Return['pn'])){
	        $Return['pn'] = 0;
	    }
	    if(empty($Return['pagesize']) || $Return['pagesize'] < MIN_PAGESIZE || $Return['pagesize'] > MAX_PAGESIZE){
	        $Return['pagesize'] = MIN_PAGESIZE;
	    }
	    if(empty($Return['p']) || $Return['p'] < 1){
	        $Return['p'] = 1;
	    }elseif (!empty($Return['pn']) && $Return['p'] > $Return['pn']){
	        $Return['p'] = $Return['pn'];
	    }
	    return $Return;
	}
	
}//end Base_Controller
