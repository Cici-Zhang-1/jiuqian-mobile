<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_linker/add'] = array(
                        array (
            'field' => 'dealer_id',
            'label' => '客户id',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
    array (
        'field' => 'shop_id',
        'label' => '店面id',
        'rules' => 'trim|numeric|max_length[10]'
    ),
                                array (
            'field' => 'name',
            'label' => '联系人用户名',
            'rules' => 'trim|required|max_length[32]|is_unique[dealer_linker.dl_name]'
        ),
                                array (
            'field' => 'truename',
            'label' => '真实姓名',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'password',
            'label' => '密码',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'position',
            'label' => '职位',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'mobilephone',
            'label' => '联系人联系移动电话',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'telephone',
            'label' => '联系人联系座机',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'email',
            'label' => '联系人联系email',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'qq',
            'label' => '联系人联系qq',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'fax',
            'label' => '联系人联系传真',
            'rules' => 'trim|max_length[16]'
        ),
    array (
        'field' => 'primary',
        'label' => '首要联系人',
        'rules' => 'trim|required|numeric|max_length[1]'
    )
            );

$config['dealer/dealer_linker/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
    array (
        'field' => 'dealer_id',
        'label' => '客户id',
        'rules' => 'trim|required|numeric|max_length[10]'
    ),
                                array (
            'field' => 'name',
            'label' => '联系人用户名',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'truename',
            'label' => '真实姓名',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'password',
            'label' => '密码',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'position',
            'label' => '职位',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'mobilephone',
            'label' => '店员联系移动电话',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'telephone',
            'label' => '联系座机',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'email',
            'label' => '联系email',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'qq',
            'label' => '联系qq',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'fax',
            'label' => '联系传真',
            'rules' => 'trim|max_length[16]'
        ),
    array (
        'field' => 'primary',
        'label' => '首要联系人',
        'rules' => 'trim|required|numeric|max_length[1]'
    )
            );

$config['dealer/dealer_linker/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                                        );
