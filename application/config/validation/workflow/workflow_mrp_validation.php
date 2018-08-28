<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_mrp/add'] = array(
                        array (
            'field' => 'id',
            'label' => 'v',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'previous',
            'label' => '前置节点',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'next',
            'label' => '后置节点',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'file',
            'label' => '执行文件',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'type',
            'label' => '类型',
            'rules' => 'trim|required|max_length[64]'
        )
            );

$config['workflow/workflow_mrp/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'id',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'previous',
            'label' => '前置节点',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'next',
            'label' => '后置节点',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'file',
            'label' => '执行文件',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'type',
            'label' => '类型',
            'rules' => 'trim|required|max_length[64]'
        )
            );

$config['workflow/workflow_mrp/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[4]'
        )
                                );
