<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_board_door/add'] = array(
                        array (
            'field' => 'order_product_board_id',
            'label' => '订单产品',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'width',
            'label' => '宽度',
            'rules' => 'trim|required|decimal|min_length[1]'
        ),
                                array (
            'field' => 'length',
            'label' => '长度',
            'rules' => 'trim|required|decimal|min_length[1]'
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
            'field' => 'left_edge',
            'label' => '左封边',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'right_edge',
            'label' => '右封边',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'up_edge',
            'label' => '上封边',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'down_edge',
            'label' => '下封边',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'open_hole',
            'label' => '开孔',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'handle',
            'label' => '把手',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'invisibility',
            'label' => '隐形拉手',
            'rules' => 'trim|decimal|min_length[1]'
        )
            );

$config['order/order_product_board_door/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_product_board_id',
            'label' => '订单产品',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'width',
            'label' => '宽度',
            'rules' => 'trim|required|decimal|min_length[1]'
        ),
                                array (
            'field' => 'length',
            'label' => '长度',
            'rules' => 'trim|required|decimal|min_length[1]'
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
            'field' => 'left_edge',
            'label' => '左封边',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'right_edge',
            'label' => '右封边',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'up_edge',
            'label' => '上封边',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'down_edge',
            'label' => '下封边',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'open_hole',
            'label' => '开孔',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'handle',
            'label' => '把手',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'invisibility',
            'label' => '隐形拉手',
            'rules' => 'trim|decimal|min_length[1]'
        )
            );

$config['order/order_product_board_door/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                                            );
