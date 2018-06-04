<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse_order_product/add'] = array(
                        array (
            'field' => 'warehouse_num',
            'label' => 'warehouse_num',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'order_product_id',
            'label' => 'order_product_id',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'creator',
            'label' => 'creator',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => 'create_datetime',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'picker',
            'label' => 'picker',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'pick_datetime',
            'label' => 'pick_datetime',
            'rules' => 'trim|'
        )
            );

$config['warehouse/warehouse_order_product/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'warehouse_num',
            'label' => 'warehouse_num',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'order_product_id',
            'label' => 'order_product_id',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'creator',
            'label' => 'creator',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => 'create_datetime',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'picker',
            'label' => 'picker',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'pick_datetime',
            'label' => 'pick_datetime',
            'rules' => 'trim|'
        )
            );

$config['warehouse/warehouse_order_product/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        )
                            );
