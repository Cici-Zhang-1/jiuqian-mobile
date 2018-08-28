<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['drawing/drawing/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'type',
            'label' => '类型',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'order_product_id',
            'label' => '产品',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'path',
            'label' => '路径',
            'rules' => 'trim|required|max_length[256]'
        )
            );

$config['drawing/drawing/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'type',
            'label' => '类型',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'order_product_id',
            'label' => '产品',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'path',
            'label' => '路径',
            'rules' => 'trim|required|max_length[256]'
        )
            );

$config['drawing/drawing/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                    );
