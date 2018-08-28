<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse_area/add'] = array(
                        array (
            'field' => 'num',
            'label' => 'v',
            'rules' => 'trim|required|max_length[2]|is_unique[warehouse_area.wa_num]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['warehouse/warehouse_area/enable'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[2]'
        )
            );

$config['warehouse/warehouse_area/unable'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|max_length[2]'
    )
);

$config['warehouse/warehouse_area/remove'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|max_length[2]'
    )
);
