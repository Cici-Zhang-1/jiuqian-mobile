<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_board_wood/add'] = array(
                        array (
            'field' => 'order_product_board_id',
            'label' => '订单产品板材',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'wood_name',
            'label' => '木框门名称',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'width',
            'label' => '宽度',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'length',
            'label' => '长度',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'area',
            'label' => '面积',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'amount',
            'label' => '数量',
            'rules' => 'trim|numeric|max_length[2]'
        ),
                                array (
            'field' => 'punch',
            'label' => '打孔',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'core',
            'label' => '门芯',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'center',
            'label' => '中横',
            'rules' => 'trim|max_length[64]'
        )
            );

$config['order/order_product_board_wood/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_product_board_id',
            'label' => '订单产品板材',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'wood_name',
            'label' => '木框门名称',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'width',
            'label' => '宽度',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'length',
            'label' => '长度',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'area',
            'label' => '面积',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'amount',
            'label' => '数量',
            'rules' => 'trim|numeric|max_length[2]'
        ),
                                array (
            'field' => 'punch',
            'label' => '打孔',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'core',
            'label' => '门芯',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'center',
            'label' => '中横',
            'rules' => 'trim|max_length[64]'
        )
            );

$config['order/order_product_board_wood/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                            );
