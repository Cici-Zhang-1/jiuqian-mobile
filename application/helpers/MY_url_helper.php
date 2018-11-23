<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2014-9-26
 * @author ZhangCC
 * @version
 * @description  
 */
if(! function_exists('m_url')){
	function m_url($Uri){
		static $Uris = array();
		if(!isset($Uris[$Uri])){
			$CI = &get_instance();
			$BaseUrl = $CI->config->item('uris');
			if(isset($BaseUrl[$Uri])){
				$Uris[$Uri] = site_url($BaseUrl[$Uri]);
			}else{
				$Uris[$Uri] = site_url($BaseUrl['default']);
			}
		}
		return $Uris[$Uri];
	}
}

if(! function_exists('drawing_url')){
    /**
     * 生成图纸url
     * @param $String
     * @return string
     */
    function drawing_url($String){
        return base_url(substr($String, strlen(ROOTDIR)));
    }
}


if (! function_exists('pub_url')) {
    function pub_url($Uri = '') {
        $CI = &get_instance();
        return trim(trim($CI->config->item('pub_url'), '/') . '/' . trim($Uri, '/'), '/');
    }
}