<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['activity/activity/add'] = array(
                        array (
            'field' => 'title',
            'label' => '标题',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'content',
            'label' => '内容',
            'rules' => 'trim|max_length[256]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        ),
    array (
        'field' => 'notice',
        'label' => '主页',
        'rules' => 'trim|numeric|max_length[1]'
    )
            );

$config['activity/activity/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'title',
            'label' => '标题',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'content',
            'label' => '内容',
            'rules' => 'trim|max_length[256]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        ),
    array (
        'field' => 'notice',
        'label' => '主页',
        'rules' => 'trim|numeric|max_length[1]'
    )
            );

$config['activity/activity/stop'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                    );

$config['activity/activity/start'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
