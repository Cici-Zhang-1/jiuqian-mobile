<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/mrp/add'] = array(
                        array (
            'field' => 'batch_num',
            'label' => '批次编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'board',
            'label' => '板块',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'num',
            'label' => '数量',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'distribution',
            'label' => '分配',
            'rules' => 'trim|numeric|max_length[10]'
        )
            );
$config['order/mrp/distribution'] = array(
    array(
        'field' => 'v[]',
        'label' => '编号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array (
        'field' => 'distribution',
        'label' => '分配',
        'rules' => 'trim|numeric|max_length[10]'
    )
);

$config['order/mrp/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'num',
            'label' => '数量',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
            );

$config['order/mrp/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                        );

$config['order/mrp/re_shear'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
