<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/26
 * Time: 8:23
 */

$config['order/punch/edit'] = array(
    array(
        'field' => 'v[]',
        'label' => '确认打孔的订单',
        'rules' => 'required|numeric|min_length[1]|max_length[10]'
    )
);