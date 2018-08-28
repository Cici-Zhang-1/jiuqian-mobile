<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/product/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'parent',
            'label' => '父类',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'code',
            'label' => '代号',
            'rules' => 'trim|required|max_length[4]'
        ),
    array (
        'field' => 'production_line',
        'label' => '生产线',
        'rules' => 'trim|numeric|max_length[4]'
    ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['product/product/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'parent',
            'label' => '父类',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'code',
            'label' => '代号',
            'rules' => 'trim|required|max_length[4]'
        ),
    array (
        'field' => 'production_line',
        'label' => '生产线',
        'rules' => 'trim|numeric|max_length[4]'
    ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['product/product/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                            );
