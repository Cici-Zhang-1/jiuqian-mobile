<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['desk/print_task/add'] = array(
                        array (
            'field' => 'file',
            'label' => '执行文件',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'source_id',
            'label' => '源ID',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建日期',
            'rules' => 'trim|required'
        )
            );

$config['desk/print_task/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'file',
            'label' => '执行文件',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'source_id',
            'label' => '源ID',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建日期',
            'rules' => 'trim|required'
        )
            );

$config['desk/print_task/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                        );
