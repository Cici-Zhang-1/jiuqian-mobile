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
        if (is_array($Param)) {
            if (!empty($Param)) {
                foreach ($Param as $Key => $Value) {
                    $Param[$Key] = array_to_string($Value);
                }
                $String = implode('', $Param);
            } else {
                $String = '';
            }
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

if (!function_exists('combos')) {
    /**
     * 级联多维数组
     * @param $data
     * @param array $all
     * @param array $group
     * @param null $val
     * @param int $i
     * @return array
     */
    function combos($data, &$all = array(), $group = array(), $val = null, $i = 0) {
        if (isset($val)) {
            array_push($group, $val);
        }

        if ($i >= count($data)) {
            array_push($all, $group);
        } else {
            foreach ($data[$i] as $v) {
                combos($data,$all, $group, $v, $i + 1);
            }
        }

        return $all;
    }
}
