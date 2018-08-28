<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_delivery/add'] = array(
                        array (
            'field' => 'dealer_id',
            'label' => '客户',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
    array (
        'field' => 'shop_id',
        'label' => '店面id',
        'rules' => 'trim|numeric|max_length[10]'
    ),
                                array (
            'field' => 'area_id',
            'label' => '地区',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'address',
            'label' => '地址',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'logistics',
            'label' => '物流',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'out_method',
            'label' => '要求出厂方式',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'linker',
            'label' => '收货人',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'phone',
            'label' => '联系电话',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'primary',
            'label' => '首要发货方式',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['dealer/dealer_delivery/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'dealer_id',
            'label' => '客户',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'area_id',
            'label' => '地区',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'address',
            'label' => '地址',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'logistics',
            'label' => '物流',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'out_method',
            'label' => '要求出厂方式',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'linker',
            'label' => '收货人',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'phone',
            'label' => '联系电话',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'primary',
            'label' => '首要发货方式',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['dealer/dealer_delivery/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                    );
