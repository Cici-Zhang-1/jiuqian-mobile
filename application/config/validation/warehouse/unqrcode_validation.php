<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/unqrcode/add'] = array(
                        array (
            'field' => 'order_product_num',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'product',
            'label' => '产品名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'pack',
            'label' => '包装件数',
            'rules' => 'trim|required|numeric|max_length[4]'
        )
            );

$config['warehouse/unqrcode/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'product',
            'label' => '产品名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'pack',
            'label' => '包装件数',
            'rules' => 'trim|required|numeric|max_length[4]'
        )
            );

$config['warehouse/unqrcode/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                    );
