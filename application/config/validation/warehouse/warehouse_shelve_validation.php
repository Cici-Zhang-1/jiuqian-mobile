<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse_shelve/add'] = array(
                        array (
            'field' => 'num',
            'label' => 'v',
            'rules' => 'trim|required|max_length[4]|is_unique[warehouse_shelve.ws_num]'
        ),
                                array (
            'field' => 'width',
            'label' => '货架宽度',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'height',
            'label' => '货架高度',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'warehouse_area_num',
            'label' => '所属仓库',
            'rules' => 'trim|required|max_length[2]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[512]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['warehouse/warehouse_shelve/enable'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[4]'
        )
                            );

$config['warehouse/warehouse_shelve/unable'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|max_length[4]'
    )
);

$config['warehouse/warehouse_shelve/remove'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|max_length[4]'
    )
);
