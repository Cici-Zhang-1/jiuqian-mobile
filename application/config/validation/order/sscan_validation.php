<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/26
 * Time: 8:23
 */

$config['order/sscan/edit'] = array(
    array(
        'field' => 'v[]',
        'label' => '确认扫描的订单',
        'rules' => 'required|numeric|min_length[1]|max_length[10]'
    )
);

$config['order/sscan/correct'] = array(
    array(
        'field' => 'v[]',
        'label' => '需要校正的订单',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array(
        'field' => 'sscanner',
        'label' => '扫描人',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);