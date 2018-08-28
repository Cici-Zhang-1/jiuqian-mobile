<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_door/add'] = array(
                        array (
            'field' => 'order_product_id',
            'label' => '订单产品',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'board',
            'label' => '板材',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'edge',
            'label' => '封边',
            'rules' => 'trim|max_length[64]'
        )
            );

$config['order/order_product_door/edit'] = array(
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
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'edge',
            'label' => '封边',
            'rules' => 'trim|max_length[64]'
        )
            );

$config['order/order_product_door/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                );
