<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月3日
 * @author Zhangcc
 * @version
 * @des
 */
if ( ! function_exists('generate_edge_thick'))
{
    /**
     * dh_mysqsl_string
     * 计算橱柜、衣柜、门板的封边厚度
     * @access public
     * @param string|array $_string
     * @return string|array
     */
    function generate_edge_thick($Value, $Type = '0'){
        $Return = array();
        switch ($Type){
            case 'w':
                $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
                break;
            case 'y':
                /*if('HHHH' == $Value['edge']){
                    $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1.5;
                }elseif ('bbbb' == $Value['edge']){
                    $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
                }elseif ('Hb' == $Value['edge']){
                    $Return['left_edge'] = $Return['up_edge'] = 1.5;
                    $Return['right_edge'] = $Return['down_edge'] = 0;
                }elseif ('Hbbb' == $Value['edge']){
                    $Return['up_edge'] = 1.5;
                    $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = 1;
                }else{
                    $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = 1;
                }*/
                /*if('HHHH' == $Value['edge']){
                    $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = H_EDGE;
                }elseif('4H' == $Value) {
                    $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = I_EDGE;
                }elseif('3H' == $Value) {
                    $Return['left_edge'] = $Return['up_edge'] = $Return['down_edge'] = I_EDGE;
                    $Return['right_edge'] = O_EDGE;
                }elseif ('bbbb' == $Value['edge']){
                    $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = B_EDGE;
                }elseif ('Hb' == $Value['edge']){
                    $Return['left_edge'] = $Return['up_edge'] = H_EDGE;
                    $Return['right_edge'] = $Return['down_edge'] = O_EDGE;
                }elseif ('Hbbb' == $Value['edge']){
                    $Return['up_edge'] = H_EDGE;
                    $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = B_EDGE;
                }else{
                    $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = B_EDGE;
                }*/
                $_CI = &get_instance();
                $_CI->model('data/wardrobe_edge_model');
                $Return = array();
                if (!!($Edges = $_CI->wardrobe_edge_model->select_wardrobe_edge_by_name(gh_escape($Value['edge'])))) {
                    if (!empty($Edges['ups'])) {
                        $Return['up_edge'] = $Edges['ups'];
                    }else {
                        $Return['up_edge'] = O_EDGE;
                    }
                    if (!empty($Edges['downs'])) {
                        $Return['down_edge'] = $Edges['downs'];
                    }else {
                        $Return['down_edge'] = O_EDGE;
                    }
                    if (!empty($Edges['lefts'])) {
                        $Return['left_edge'] = $Edges['lefts'];
                    }else {
                        $Return['left_edge'] = O_EDGE;
                    }
                    if (!empty($Edges['rights'])) {
                        $Return['right_edge'] = $Edges['rights'];
                    }else {
                        $Return['right_edge'] = O_EDGE;
                    }
                }else {
                    $Return['up_edge'] = $Return['left_edge'] = $Return['right_edge'] = $Return['down_edge'] = O_EDGE;
                }
                break;
            case 'm':
                if(preg_match('/双色/',$Value)){   //长、宽
                    $Return['up_edge'] = $Return['left_edge'] = 1.1;
                    $Return['right_edge'] = $Return['down_edge'] = 0;
                }elseif (preg_match('/同色/',$Value)){
                    $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
                }elseif(preg_match('/哑光窄边/',$Value)){
                    $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
                }elseif (preg_match('/碰角/',$Value)){
                    $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1.5;
                }else{
                    $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 1;
                }
                break;
            default:
                $Return['left_edge'] = $Return['right_edge'] = $Return['up_edge'] = $Return['down_edge'] = 0;
        }
        return $Return;
    }
}

if (!function_exists('get_face')) {
    function get_face() {
        $CI = &get_instance();
        $CI->load->model('data/face_model');
        $Face = array();
        if (!!($Query = $CI->face_model->select_face())) {
            foreach ($Query as $value) {
                $Face[$value['wardrobe_punch'] . $value['wardrobe_slot']] = $value['flag'];
            }
        }

        return $Face;
    }
}