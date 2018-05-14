<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年2月25日
 * @author Administrator
 * @version
 * @des
 * 数组
 */
if(! function_exists('element_sum')){
    function element_sum($Array1, $Array2){
        foreach ($Array1 as $key => $value){
            $Array1[$key] += $Array2[$key];
            unset($Array2[$key]);
        }
        $Array1 = array_merge($Array1, $Array2);
        return $Array1;
    }
}

if(! function_exists('array_to_string')) {
    /**
     * Array to String
     * @param array $Param
     * @return array|string
     */
    function array_to_string($Param = array()) {
        if (is_array($Param) && count($Param) > 0) {
            foreach ($Param as $Key => $Value) {
                $Param[$Key] = array_to_string($Value);
            }
            $String = implode('', $Param);
            return $String;
        } else {
            return $Param;
        }
    }
}

if (!function_exists('valid_array')) {
    /**
     * is valid array
     * @param array $Array
     * @return bool
     */
    function valid_array($Array = array()) {
        return isset($Array) && is_array($Array) && count($Array) > 0;
    }
}
