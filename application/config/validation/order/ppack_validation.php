<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/26
 * Time: 8:24
 */

$config['order/ppack/edit'] = array(
    array(
        'field' => 'v[]',
        'label' => '确认打包的订单',
        'rules' => 'required|numeric|min_length[1]|max_length[10]'
    )
);

$config['order/ppack/correct'] = array(
    array(
        'field' => 'v[]',
        'label' => '需要校正的订单',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array(
        'field' => 'ppacker',
        'label' => '打包人',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);