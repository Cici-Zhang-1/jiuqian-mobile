<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse_area/add'] = array(
                        array (
            'field' => 'num',
            'label' => 'num',
            'rules' => 'trim|max_length[2]'
        )
            );

$config['warehouse/warehouse_area/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|max_length[2]'
        ),
                                array (
            'field' => 'num',
            'label' => 'num',
            'rules' => 'trim|max_length[2]'
        )
            );

$config['warehouse/warehouse_area/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|max_length[2]'
        )
        );
