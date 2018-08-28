<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_wardrobe_struct/add'] = array(
                        array (
            'field' => 'order_product_id',
            'label' => '订单产品',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'board',
            'label' => '板材',
            'rules' => 'trim|max_length[64]'
        )
            );

$config['order/order_product_wardrobe_struct/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_product_id',
            'label' => '订单产品',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'board',
            'label' => '板材',
            'rules' => 'trim|max_length[64]'
        )
            );

$config['order/order_product_wardrobe_struct/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
            );
