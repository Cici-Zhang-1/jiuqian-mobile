<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/configs/add'] = array(
                        array (
            'field' => 'type',
            'label' => '配置类型',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|is_unique[configs.c_name]|max_length[64]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'config',
            'label' => '值',
            'rules' => 'trim|required|max_length[1024]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['data/configs/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'type',
            'label' => '配置类型',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'config',
            'label' => '值',
            'rules' => 'trim|required|max_length[1024]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['data/configs/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        )
                        );
