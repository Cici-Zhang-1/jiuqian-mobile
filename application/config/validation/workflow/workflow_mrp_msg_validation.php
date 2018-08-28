<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_mrp_msg/add'] = array(
                        array (
            'field' => 'mrp_id',
            'label' => 'MRPV',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建时间',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'msg',
            'label' => '信息',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'workflow_mrp_id',
            'label' => '状态节点',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'previous',
            'label' => '前置节点',
            'rules' => 'trim|numeric|max_length[4]'
        )
            );

$config['workflow/workflow_mrp_msg/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'mrp_id',
            'label' => 'MRPV',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建时间',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'msg',
            'label' => '信息',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'workflow_mrp_id',
            'label' => '状态节点',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'previous',
            'label' => '前置节点',
            'rules' => 'trim|numeric|max_length[4]'
        )
            );

$config['workflow/workflow_mrp_msg/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        )
                            );
