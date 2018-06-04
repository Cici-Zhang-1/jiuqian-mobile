<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse_shelve/add'] = array(
                        array (
            'field' => 'num',
            'label' => 'num',
            'rules' => 'trim|max_length[4]'
        ),
                                array (
            'field' => 'width',
            'label' => 'width',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'height',
            'label' => 'height',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'warehouse_area_num',
            'label' => 'warehouse_area_num',
            'rules' => 'trim|max_length[2]'
        ),
                                array (
            'field' => 'remark',
            'label' => 'remark',
            'rules' => 'trim|max_length[512]'
        )
            );

$config['warehouse/warehouse_shelve/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|max_length[4]'
        ),
                                array (
            'field' => 'num',
            'label' => 'num',
            'rules' => 'trim|max_length[4]'
        ),
                                array (
            'field' => 'width',
            'label' => 'width',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'height',
            'label' => 'height',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'warehouse_area_num',
            'label' => 'warehouse_area_num',
            'rules' => 'trim|max_length[2]'
        ),
                                array (
            'field' => 'remark',
            'label' => 'remark',
            'rules' => 'trim|max_length[512]'
        )
            );

$config['warehouse/warehouse_shelve/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|max_length[4]'
        )
                        );
