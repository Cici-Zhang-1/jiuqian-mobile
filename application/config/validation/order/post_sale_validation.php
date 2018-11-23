<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/post_sale/add'] = array(
        array (
            'field' => 'order_id',
            'label' => '订单',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
        array (
            'field' => 'product_id',
            'label' => '产品',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
        array (
            'field' => 'set',
            'label' => '套数',
            'rules' => 'trim|required|numeric|greater_than[0]|less_than_equal_to[20]'
        ),
        array (
            'field' => 'product',
            'label' => '产品',
            'rules' => 'trim|max_length[64]'
        ),
        array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        )
);
