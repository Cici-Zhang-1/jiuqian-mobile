<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_classify/add'] = array(
                        array (
            'field' => 'order_product_id',
            'label' => '订单产品编号',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'board',
            'label' => '板材',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'amount',
            'label' => '数量',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'area',
            'label' => '面积',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'classify_id',
            'label' => '分类',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|required|numeric|max_length[2]'
        ),
                                array (
            'field' => 'optimize',
            'label' => '优化',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'sn',
            'label' => 'SN',
            'rules' => 'trim|max_length[2]'
        ),
                                array (
            'field' => 'optimize_datetime',
            'label' => '优化日期',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'optimizer',
            'label' => '优化员',
            'rules' => 'trim|numeric|max_length[10]'
        )
            );

$config['order/order_product_classify/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_product_id',
            'label' => '订单产品编号',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'board',
            'label' => '板材',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'amount',
            'label' => '数量',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'area',
            'label' => '面积',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'classify_id',
            'label' => '分类',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|required|numeric|max_length[2]'
        ),
                                array (
            'field' => 'optimize',
            'label' => '优化',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'sn',
            'label' => 'SN',
            'rules' => 'trim|max_length[2]'
        ),
                                array (
            'field' => 'optimize_datetime',
            'label' => '优化日期',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'optimizer',
            'label' => '优化员',
            'rules' => 'trim|numeric|max_length[10]'
        )
            );

$config['order/order_product_classify/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        )
                                            );
