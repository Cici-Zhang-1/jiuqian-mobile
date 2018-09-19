<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Cici
 * Date: 2018/6/11
 * Time: 11:33
 */
if (!function_exists('discode_pack')) {
    function discode_pack ($Pack) {
        $Pack = json_decode($Pack, true);
        $PackDetail = '';
        if (is_array($Pack)) {
            foreach ($Pack as $IKey => $IValue) {
                if ($IValue > 0) {
                    if ($IKey == 'thick') {
                        $PackDetail .= ' 厚板: ' . $IValue;
                    } elseif ($IKey == 'thin') {
                        $PackDetail .= ' 薄板: ' . $IValue;
                    } else {
                        $PackDetail .= '  所有: ' . $IValue . '  ';
                    }
                }
            }
        }
        return $PackDetail;
    }
}

if (!function_exists('discode_warehouse_v')) {
    function discode_warehouse_v ($WarehouseV) {
        $WarehouseV = json_decode($WarehouseV, true);
        $Discode = array();
        if (is_array($WarehouseV)) {
            foreach ($WarehouseV as $IKey => $IValue) {
                array_push($Discode, $IValue['v']);
            }
        }
        return implode(',', $Discode);
    }
}