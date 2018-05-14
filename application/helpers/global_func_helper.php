<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*Program: FEICHI
*============================
* 
*============================
*Author: Zhangcc
*Date:2014-3-6
**/

/**
 * NAME:
 * FUNCTION:
 * @param string 
 * @return bool TRUE/FALSE
 */
function gh_login_state($param1){
	if(isset($_COOKIE[$param1])){
		return TRUE;
	}else {
		return FALSE;
	}
}

/**
* NAME: _alert_back()
* FUNCTION: 弹出警告窗口
* @param string $_info 需要弹出的信息
* @return void
 */
function gh_alert_back($_info=''){
	if (empty($_info)) {
		echo "<script type='text/javascript'>history.back();</script>";
	}else{
		gh_header_content_type();
		echo "<script type='text/javascript'>alert('$_info');history.back();</script>";
	}
	exit();
}

if(! function_exists('gh_alert')){
	function gh_alert($_info){
		gh_header_content_type();
		echo '<script type="text/javascript">alert("'.$_info.'")</script>';
		exit();
	}
}

/**
 * NAME: gh_location
 * FUNCTION: 重定向
 * @param string $_string
 * @param string $_url
 */
function gh_location($_string,$_url){
	if(!empty($_string)){
		header('Content-Type:text/html;charset=utf-8');
		echo "<script type='text/javascript'>alert('$_string');location.href='$_url';</script>";
		exit();
	}else {
		header('Location:'.$_url);
		exit();
	}
}

/**
 * FUNCTION: Visit Return
 * @param $Code
 * @param string $Message
 * @param string $Location
 * @param array $Data
 */
function gh_return($Code, $Message = '', $Location = '', $Data = array()) {
    if (preg_match('/json/', $_SERVER['HTTP_ACCEPT'])) {
        exit(json_encode(array(
            'error' => $Code,
            'message' => $Message,
            'data' => $Data,
            'location' => $Location
        )));
    }else {
        if ($Code > EXIT_SUCCESS) {
            show_error($Message);
        }else {
            exit();
        }
    }
}

/**
 * 输出格式和字符集
 */

if(!function_exists('gh_header_content_type')){
    function gh_header_content_type(){
        header('Content-Type:text/html;charset=utf-8');
    }
}

if(!function_exists('isAjax')){
	function isAjax() {
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
			if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
				return true;
		}
		//if(!empty($_POST) || !empty($_GET))
			//return true;
		return false;
	}
}

if(! function_exists('get_jquery')){
	function get_jquery(){
		$CI = &get_instance();
		$CI->load->library('user_agent');
		/****jQuery版本控制******************************/
		if($CI->agent->browser() == 'Internet Explorer' && floatval($CI->agent->version()) <= 8.0){
			return 'http://cdn.bootcss.com/jquery/1.11.1-rc2/jquery.min.js';
		}else{
			return 'http://cdn.bootcss.com/jquery/2.1.1-rc2/jquery.min.js';
		}
	}
}
/**
 * Base URL
 *
 * Create a local URL based on your basepath.
 * Segments can be passed in as a string or an array, same as site_url
 * or a URL to a file can be passed in, e.g. to an image file.
 *
 * @access	public
 * @param string
 * @return	string
 */
/* if ( ! function_exists('gh_base_url'))
{
	function gh_base_url($uri = '')
	{
		$CI =& get_instance();
		return $CI->config->base_url($uri);
	}
} */

if(! function_exists('gh_to_sec')){
	/**
	 * 日期转换为unixtimestamp
	 * @param unknown $SParam1 xxx年xx月xx日
	 * @return number
	 */
	function gh_to_sec($SParam1){
		if(!empty($SParam1)){
			if(preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})\s([0-9]{2}):([0-9]{2}):([0-9]{2})$/', $SParam1)){
				$Tmp = explode(' ', $SParam1);
				$Tmp0 = explode('-',  $Tmp[0]);
				$Tmp1 = explode(':', $Tmp[1]);
				$ADate = array_merge($Tmp0, $Tmp1);
			}elseif(preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $SParam1)){
				$ADate = explode('-', $SParam1);
			}else{
				$reg = "/^([0-9]{4})年([0-9]{2})月([0-9]{2})日$/";
				$ADate = explode('-', preg_replace($reg, '$1-$2-$3', $SParam1));
			}
			
			if(count($ADate) == 6){
				return mktime($ADate[4],$ADate[5],$ADate[6],$ADate[1],$ADate[2],$ADate[0]);
			}elseif(count($ADate) == 3){
				if($ADate[0] >= 1970){
					return mktime(0,0,0,$ADate[1],$ADate[2],$ADate[0]);
				}else{
					return mktime(0,0,0,$ADate[1],$ADate[2],$ADate[0]);
				}
			}else{
				return strtotime($SParam1);
			}
		}else{
			return mktime(0,0,0,date('m'),date('d'),date('Y'));
		}
	}
}

/**
 * 日期英中文转换
 * @param unknown $Time
 */
function gh_week_cn($Time){
	$CI = & get_instance();
	$CI->lang->load('calendar');
	$Date = getdate($Time);
	return $CI->lang->line('cal_'.strtolower($Date['weekday']));
}

/**
 * NAME:
 * FUNCTION:字符串截取
 * @param unknown $_string
 * @return string
 */
function gh_title($_string){
	if(mb_strlen($_string,'utf-8')>14){
		$_string = mb_substr($_string,1,14,'utf-8').'...';
	}
	return $_string;
}

/**
 * gbk转icov
 * @param unknown $MParam1
 * @return string
 */
function gh_gbk_utf($MParam1){
	if(is_array($MParam1)){
		foreach ($MParam1 as $index => $value){
			$MParam1[$index] =gh_gbk_utf($value);
		}
	}else{
		if(!mb_detect_encoding($MParam1, 'utf-8', true)){
			return iconv("gbk", "UTF-8//IGNORE",  $MParam1);
		}else{
			return $MParam1;
		}
	}
}
/**
 * js解码
 * @param unknown $MParam1
 * @return unknown
 */
function gh_js_decode($MParam1){
	if(is_array($MParam1)){
		foreach ($MParam1 as $index => $value){
			$MParam1[$index] =gh_js_decode($value);
		}
	}else{
		$str = rawurldecode($MParam1);
		preg_match_all("/(?:%u.{4})|&#x.{4};|&#\d+;|.+/U",$str,$r);
		$ar = $r[0];
		foreach($ar as $k=>$v) {
			if(substr($v,0,2) == "%u"){
				$ar[$k] = iconv("UCS-2BE","UTF-8",pack("H4",substr($v,-4)));
			}
			elseif(substr($v,0,3) == "&#x")
			$ar[$k] = iconv("UCS-2BE","UTF-8",pack("H4",substr($v,3,-1)));
			elseif(substr($v,0,2) == "&#") {
				echo substr($v,2,-1)."<br>";
				$ar[$k] = iconv("UCS-2BE","UTF-8",pack("n",substr($v,2,-1)));
			}
		}
		return join("",$ar);
	}
	return $MParam1;
}
/**
 * 下划线连接字符串转化为驼峰类字符串
 * @param unknown $Name
 * @return mixed|string
 */
function name_to_id($Name, $First = false){
	if(is_string($Name)){
	    if ($First) {
            return preg_replace('/\s/','',ucwords(preg_replace('/_/', ' ', $Name)));
        }else {
            return preg_replace('/\s/','',gh_lcfirst(ucwords(preg_replace('/_/', ' ', $Name))));
        }
	}else{
		return '';
	}
}



/**
 * 定长字符串换行
 * @param unknown $items 
 * @param unknown $key
 * @param string $Length
 */
function gh_str_br(&$items, $key, $Length=32){
	if(function_exists('mb_strlen')){
		if(mb_strlen($items) > $Length){
			$End = (mb_strlen($items)/$Length+1);
			for($i = 0; $i<$End; $i++){
				$Tmp[$i] = mb_substr($items, $i*$Length, $Length);
			}
			$items = implode('<br />', $Tmp);
		}
	}else{
		if(strlen($items) > $Length){
			$End = (mb_strlen($items)/$Length+1);
			for($i = 0; $i<$End; $i++){
				$Tmp[$i] = utf_substr($items, $i*$Length, $Length);
			}
			$items = implode('<br />', $Tmp);
		}
	}
}

if(! function_exists('utf_substr')){
	/**
	 * UTF-8格式字符串截取函数
	 * @param unknown $str
	 * @param number $start
	 * @param unknown $len
	 */
	function utf_substr($str,$start=0, $len)
	{
		if($start !=0 &&ord(substr($str, $start-1,1)) > 127){
			$start = $start-1;
		}
		for($i=0;$i<$len;$i++)
		{
			$temp_str=substr($str,$start,1);
			if(ord($temp_str) > 127)
			{
				$i++;
				if($i<$len)
				{
					$new_str[]=substr($str,0,3);
					$str=substr($str,3);
				}
			}
			else
			{
				$new_str[]=substr($str,0,1);
				$str=substr($str,1);
			}
		}
		return join($new_str);
	}
}

if(! function_exists('gh_lcfirst')){
	/**
	 * 将字符串首字母编程小写
	 * @param unknown $string 字符串
	 * @return string|unknown 字符串
	 */
	function gh_lcfirst($string){
		if(is_string($string) && strlen($string)>0){
			if(function_exists('lcfirst')){
				return lcfirst($string);
			}else{
				$First = ord(substr($string, 0,1));
				if($First >= 65&&$First <= 90)
					$First = chr($First + 32);
				return $First.substr($string, 1);
			}
		}
		return $string;
	}
}

if ( !function_exists('mb_detect_encoding') ) {
	/**
	 * 检测字符串编码格式
	 * @param unknown $string 字符串
	 * @param string $enc 编码
	 * @param string $ret 指定返回类型
	 * @return Ambigous <boolean, string>
	 */
	function mb_detect_encoding ($string, $enc=null, $ret=null) {
		static $enclist = array(
				'UTF-8', 'ASCII',
				'ISO-8859-1', 'ISO-8859-2', 'ISO-8859-3', 'ISO-8859-4', 'ISO-8859-5',
				'ISO-8859-6', 'ISO-8859-7', 'ISO-8859-8', 'ISO-8859-9', 'ISO-8859-10',
				'ISO-8859-13', 'ISO-8859-14', 'ISO-8859-15', 'ISO-8859-16',
				'Windows-1251', 'Windows-1252', 'Windows-1254',
		);
		$result = false;
		foreach ($enclist as $item) {
			$sample = iconv($item, $item, $string);
			if (md5($sample) == md5($string)) {
				if ($ret === NULL) { $result = $item; } else { $result = true; }
				break;
			}
		}
		return $result;
	}
}

/**
 * 转义数据库中字符
 */
if(! function_exists('gh_escape')){
    function gh_escape($_string){
        if (is_array($_string)) {
            foreach ($_string as $_key=>$_value){
                $_string[$_key] = gh_escape($_value);
            }
        }elseif(is_string($_string)){
            $_string = htmlspecialchars($_string);
        }
        return $_string;
    }
}
if(! function_exists('gh_mysql_string')){
	/**
	 * 字符串转码
	 * @param unknown $_string
	 * @return string
	 */
	function gh_mysql_string($_string){
		if (is_array($_string)) {
			foreach ($_string as $_key=>$_value){
				$_string[$_key] = gh_mysql_string($_value);
			}
		}elseif(is_string($_string)){
			if ( ! is_php('5.4') && get_magic_quotes_gpc()){
				$_string = mysql_real_escape_string(htmlspecialchars(stripslashes($_string)));
			}else{
				$_string = mysql_real_escape_string(htmlspecialchars($_string));
			}
		}
		return $_string;
	}
}

if(! function_exists('gh_replace')){
	function gh_str_replace($str){
		return str_replace('*', '-', $str);
	}
}

if(! function_exists('gh_json_decode')){
	function gh_json_decode(){
		if(count($_POST) > 0){
			foreach ($_POST as $key => $value){
				if(!is_array($value) && preg_match('/^\[.*\]$/i', $value)){
					$_POST[$key] = json_decode($value);
				}
			}
		}
		return;
	}
}

if(! function_exists('str_add_br')){
	function str_add_br($Str){
		if(strlen($Str) > 30){
			$A = explode(',', $Str);
			for($i = 0; $i < count($A); $i = $i + 3){
				$B[] = implode(',', array_slice($A, $i,3));
			}
			$Str = implode(',<br />', $B);
		}
		return $Str;
	}
}

if(! function_exists('gh_infinity_category')){
	function gh_infinity_category($Tree, $Parent = 0){
		$Return = array();
		if(is_array($Tree) && count($Tree) > 0 && isset($Tree[$Parent])){
			foreach ($Tree[$Parent] as $key => $value){
				$Return[] = $value;
				if(isset($Tree[$value])){
					$Tmp = gh_infinity_category($Tree, $value);
					$Return = array_merge($Return, $Tmp);
				}
			}
		}
		return $Return;
	}
}
