<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/classify/add'] = array(
        array (
            'field' => 'parent',
            'label' => '父类',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'flag',
            'label' => '标志',
            'rules' => 'trim|max_length[8]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'print_list',
            'label' => '打印',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'label',
            'label' => '标签',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'optimize',
            'label' => '优化',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'plate',
            'label' => '板块名称',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'width_min',
            'label' => '最窄',
            'rules' => 'trim|decimal|min_length[1]|max_length[6]'
        ),
                                array (
            'field' => 'width_max',
            'label' => '最宽',
            'rules' => 'trim|decimal|min_length[1]|max_length[6]'
        ),
                                array (
            'field' => 'length_min',
            'label' => '最短',
            'rules' => 'trim|decimal|min_length[1]|max_length[6]'
        ),
                                array (
            'field' => 'length_max',
            'label' => '最长',
            'rules' => 'trim|decimal|min_length[1]|max_length[6]'
        ),
                                array (
            'field' => 'thick',
            'label' => '厚度',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'edge',
            'label' => '封边',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'slot',
            'label' => '开槽',
            'rules' => 'trim|max_length[64]'
        ),
    array (
        'field' => 'decide_size',
        'label' => '尺寸判定',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'abnormity',
        'label' => '异形',
        'rules' => 'trim|numeric|max_length[1]'
    ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'production_line',
            'label' => '生产线',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['data/classify/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'parent',
            'label' => '父类',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'flag',
            'label' => '标志',
            'rules' => 'trim|max_length[8]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'print_list',
            'label' => '打印',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'label',
            'label' => '标签',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'optimize',
            'label' => '优化',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'plate',
            'label' => '板块名称',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'width_min',
            'label' => '最窄',
            'rules' => 'trim|decimal|min_length[1]|max_length[6]'
        ),
                                array (
            'field' => 'width_max',
            'label' => '最宽',
            'rules' => 'trim|decimal|min_length[1]|max_length[6]'
        ),
                                array (
            'field' => 'length_min',
            'label' => '最短',
            'rules' => 'trim|decimal|min_length[1]|max_length[6]'
        ),
                                array (
            'field' => 'length_max',
            'label' => '最长',
            'rules' => 'trim|decimal|min_length[1]|max_length[6]'
        ),
                                array (
            'field' => 'thick',
            'label' => '厚度',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'edge',
            'label' => '封边',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'slot',
            'label' => '开槽',
            'rules' => 'trim|max_length[64]'
        ),
    array (
        'field' => 'decide_size',
        'label' => '尺寸判定',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'abnormity',
        'label' => '异形',
        'rules' => 'trim|numeric|max_length[1]'
    ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'production_line',
            'label' => '生产线',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['data/classify/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        )
                                                                            );

$config['data/classify/stop'] = array(
    array(
        'field' => 'v[]',
        'label' => '停用项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
    )
);

$config['data/classify/start'] = array(
    array(
        'field' => 'v[]',
        'label' => '启用项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
    )
);
