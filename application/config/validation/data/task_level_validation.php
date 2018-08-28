<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/task_level/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'icon',
            'label' => 'icon',
            'rules' => 'trim|required|max_length[64]'
        )
            );

$config['data/task_level/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'icon',
            'label' => 'icon',
            'rules' => 'trim|required|max_length[64]'
        )
            );

$config['data/task_level/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
        )
            );
