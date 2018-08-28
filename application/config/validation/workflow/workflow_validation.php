<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow/add'] = array(
                        array (
            'field' => 'no',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'type',
            'label' => '类型',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name_en',
            'label' => 'EN',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'file',
            'label' => '执行文件',
            'rules' => 'trim|required|max_length[64]'
        )
            );

$config['workflow/workflow/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'no',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'type',
            'label' => '类型',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name_en',
            'label' => 'EN',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'file',
            'label' => '执行文件',
            'rules' => 'trim|required|max_length[64]'
        )
            );

$config['workflow/workflow/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[4]'
        )
                        );
