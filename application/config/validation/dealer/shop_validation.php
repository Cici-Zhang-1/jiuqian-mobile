<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/shop/add'] = array(
                        array (
            'field' => 'name',
            'label' => '店面名称',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'dealer_id',
            'label' => '经销商',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'area_id',
            'label' => '地区',
            'rules' => 'trim|numeric|required|max_length[10]'
        ),
                                array (
            'field' => 'address',
            'label' => '地址',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        ),
    array (
        'field' => 'dealer_delivery_area_id',
        'label' => '收货地区',
        'rules' => 'trim|numeric|max_length[10]'
    ),
    array (
        'field' => 'dealer_delivery_address',
        'label' => '收货地址',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'dealer_delivery_logistics',
        'label' => '物流',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'dealer_delivery_out_method',
        'label' => '要求出厂方式',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'dealer_delivery_linker',
        'label' => '收货人',
        'rules' => 'trim|max_length[32]'
    ),
    array (
        'field' => 'dealer_delivery_phone',
        'label' => '联系电话',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'dealer_linker_name',
        'label' => '用户名',
        'rules' => 'trim|max_length[32]'
    ),
    array (
        'field' => 'dealer_linker_truename',
        'label' => '真实姓名',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'dealer_linker_position',
        'label' => '职位',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'dealer_linker_mobilephone',
        'label' => '移动电话',
        'rules' => 'trim|max_length[16]'
    ),
    array (
        'field' => 'dealer_linker_telephone',
        'label' => '座机',
        'rules' => 'trim|max_length[16]'
    ),
    array (
        'field' => 'dealer_linker_email',
        'label' => 'Email',
        'rules' => 'trim|max_length[16]'
    ),
    array (
        'field' => 'dealer_linker_qq',
        'label' => 'QQ',
        'rules' => 'trim|max_length[16]'
    ),
    array (
        'field' => 'dealer_linker_fax',
        'label' => 'Fax',
        'rules' => 'trim|max_length[16]'
    )
            );

$config['dealer/shop/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'name',
            'label' => '店面名称',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'area_id',
            'label' => '地区',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'address',
            'label' => '地址',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['dealer/shop/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                                        );
