<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/3
 * Time: 15:04
 */
$config['order/produce_door/distribution'] = array(
    array(
        'field' => 'v[]',
        'label' => '编号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array (
        'field' => 'distribution',
        'label' => '分配',
        'rules' => 'trim|numeric|max_length[10]'
    )
);