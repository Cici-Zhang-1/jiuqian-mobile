<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse/add'] = array(
                        array (
            'field' => 'num',
            'label' => 'num',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'warehouse_shelve_num',
            'label' => 'warehouse_shelve_num',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'width',
            'label' => 'width',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'height',
            'label' => 'height',
            'rules' => 'trim|numeric|max_length[2]'
        ),
                                array (
            'field' => 'min',
            'label' => 'min',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'max',
            'label' => 'max',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'status',
            'label' => 'status',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['warehouse/warehouse/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'num',
            'label' => 'num',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'warehouse_shelve_num',
            'label' => 'warehouse_shelve_num',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'width',
            'label' => 'width',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'height',
            'label' => 'height',
            'rules' => 'trim|numeric|max_length[2]'
        ),
                                array (
            'field' => 'min',
            'label' => 'min',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'max',
            'label' => 'max',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'status',
            'label' => 'status',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['warehouse/warehouse/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|max_length[32]'
        )
                                );
