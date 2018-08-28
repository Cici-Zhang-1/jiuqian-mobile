<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse/add'] = array(
                        array (
            'field' => 'num',
            'label' => 'v',
            'rules' => 'trim|required|max_length[32]|is_unique[warehouse.w_num]'
        ),
                                array (
            'field' => 'warehouse_shelve_num',
            'label' => '货架编号',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'width',
            'label' => '列编号',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'height',
            'label' => '层编号',
            'rules' => 'trim|numeric|max_length[2]'
        ),
                                array (
            'field' => 'min',
            'label' => '最小容量',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'max',
            'label' => '最大容量',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['warehouse/warehouse/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'num',
            'label' => 'v',
            'rules' => 'trim|required|max_length[32]|is_unique[warehouse.w_num]'
        ),
                                array (
            'field' => 'warehouse_shelve_num',
            'label' => '货架编号',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'width',
            'label' => '列编号',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'height',
            'label' => '层编号',
            'rules' => 'trim|numeric|max_length[2]'
        ),
                                array (
            'field' => 'min',
            'label' => '最小容量',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'max',
            'label' => '最大容量',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['warehouse/warehouse/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[32]'
        )
                                );

$config['warehouse/warehouse/enable'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|max_length[32]'
    )
);

$config['warehouse/warehouse/unable'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|max_length[32]'
    )
);

$config['warehouse/warehouse/occupy'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|max_length[32]'
    )
);
