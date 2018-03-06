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