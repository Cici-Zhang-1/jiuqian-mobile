<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/logistics/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[64]|is_unique[logistics.l_name]'
        ),
                                array (
            'field' => 'phone',
            'label' => '联系电话',
            'rules' => 'trim|max_length[64]'
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
            'field' => 'vip',
            'label' => 'VIP',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建时间',
            'rules' => 'trim|numeric|max_length[10]'
        )
            );

$config['data/logistics/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'phone',
            'label' => '联系电话',
            'rules' => 'trim|max_length[64]'
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
            'field' => 'vip',
            'label' => 'VIP',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建时间',
            'rules' => 'trim|numeric|max_length[10]'
        )
            );

$config['data/logistics/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[64]'
        )
                                );
