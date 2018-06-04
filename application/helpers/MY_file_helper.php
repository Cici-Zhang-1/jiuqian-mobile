<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  2014-9-29
 * @author ZhangCC
 * @version
 * @description  
 */
/**
 * 删除指定缓存文件
 * @param string $SCacheFile
 */
if(! function_exists('delete_cache_files')){
	function delete_cache_files($SCacheFile){
		$return = true;
		$cachePath = APPPATH.'cache/';
		if(strstr($SCacheFile, '*')){
			$filenames =  get_filenames($cachePath);
			if (is_array($filenames) && !empty($filenames)) {
                foreach ($filenames as $value){
                    preg_match($SCacheFile, $value, $match);
                    if (!empty($match[0])) {
                        if(file_exists($cachePath.$match[0]))
                            $return = unlink($cachePath.$match[0])&&$return;
                    }
                }
            }
		}else{
			if(file_exists($cachePath.$SCacheFile))
				$return = unlink($cachePath.$SCacheFile);
		}
		return $return;
	}
}


if (! function_exists('file_expired')) {
    /**
     * FUNCTION: 文件是否过期
     * @param string $File file_name
     * @param integer $Ttl time tick
     * @return bool
     */
    function file_expired($File, $Ttl) {
        if ( ! is_file($File)) {
            return true;
        }

        $Time = FILE_FORCE_EXPIRED ? FILE_FORCE_EXPIRED : filemtime($File);

        if ($Ttl > 0 && time() > $Time + $Ttl) {
            return true;
        }
        return false;
    }
}
