<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/check/checked'] = array(
    array(
        'field' => 'v[]',
        'label' => '订单编号',
        'rules' => 'required|numeric|min_length[1]|max_length[10]'
    )
);

$config['order/check/add'] = array(
    array(
        'field' => 'v',
        'label' => '订单编号',
        'rules' => 'required|numeric|min_length[1]|max_length[10]'
    ),
    array (
        'field' => 'check_remark',
        'label' => '生产批注',
        'rules' => 'trim|required|max_length[128]'
    )
);
