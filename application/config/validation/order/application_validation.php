<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/application/add'] = array(
                        array (
            'field' => 'type',
            'label' => '申请类型',
            'rules' => 'trim|required|max_length[16]'
        ),
                                array (
            'field' => 'source_id',
            'label' => '源ID',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'source',
            'label' => '源值',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'des',
            'label' => '目标值',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'replyer',
            'label' => '回复人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'reply_datetime',
            'label' => '回复日期',
            'rules' => 'trim|'
        )
            );

$config['order/application/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['order/application/passed'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
);


$config['order/application/easy_produce'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array (
        'field' => 'a_remark',
        'label' => '备注',
        'rules' => 'trim|required|max_length[128]'
    )
);

$config['order/application/easy_delivery'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array (
        'field' => 'a_remark',
        'label' => '备注',
        'rules' => 'trim|required|max_length[128]'
    )
);
