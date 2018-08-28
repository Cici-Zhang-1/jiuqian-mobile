<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_msg/add'] = array(
                        array (
            'field' => 'model',
            'label' => 'Model',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'source_id',
            'label' => '原Id',
            'rules' => 'trim|required|numeric|max_length[10]'
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
        ),
                                array (
            'field' => 'msg',
            'label' => '信息',
            'rules' => 'trim|max_length[1024]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|required|numeric|max_length[4]'
        )
            );

$config['workflow/workflow_msg/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'model',
            'label' => 'Model',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'source_id',
            'label' => '原Id',
            'rules' => 'trim|required|numeric|max_length[10]'
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
        ),
                                array (
            'field' => 'msg',
            'label' => '信息',
            'rules' => 'trim|max_length[1024]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|required|numeric|max_length[4]'
        )
            );

$config['workflow/workflow_msg/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        )
                            );
