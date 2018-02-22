<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2014-9-29
 * @author ZhangCC
 * @version
 * @description  
 */
/**
 * 删除指定缓存文件
 * @param unknown $SCacheFile
 */
if(! function_exists('delete_cache_files')){
	function delete_cache_files($SCacheFile){
		$return = true;
		$cachePath = APPPATH.'cache/';
		if(strstr($SCacheFile, '*')){
			$filenames =  get_filenames($cachePath);
			foreach ($filenames as $value){
				preg_match($SCacheFile, $value, $match);
				if (!empty($match[0])) {
					if(file_exists($cachePath.$match[0]))
						$return = unlink($cachePath.$match[0])&&$return;
				}
			}
		}else{
			if(file_exists($cachePath.$SCacheFile))
				$return = unlink($cachePath.$SCacheFile);
		}
		return $return;
	}
}
