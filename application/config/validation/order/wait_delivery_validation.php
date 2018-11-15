<?php defined('BASEPATH') OR exit('No direct script access allowed');
$config['order/wait_delivery/edit'] = array(
    array(
        'field' => 'truck',
        'label' => '车辆',
        'rules' => 'trim|max_length[64]'
    ),
    array(
        'field' => 'train',
        'label' => '车次',
        'rules' => 'trim|max_length[64]'
    ),
    array(
        'field' => 'end_datetime',
        'label' => '发货日期',
        'rules' => 'trim|required|max_length[64]'
    ),
    array(
        'field' => 'remark',
        'label' => '备注',
        'rules' => 'trim|max_length[128]'
    ),
    array(
        'field' => 'order_product[]',
        'label' => '订单',
        'rules' => 'required'
    )
);
