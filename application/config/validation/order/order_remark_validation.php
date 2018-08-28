<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_remark/add'] = array(
                        array (
            'field' => 'order_id',
            'label' => '订单V',
            'rules' => 'trim|required|numeric|max_length[11]'
        ),
                                array (
            'field' => 'for',
            'label' => '内部备注',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|required|max_length[1024]'
        ),
                                array (
            'field' => 'status',
            'label' => '订单状态',
            'rules' => 'trim|numeric|max_length[4]'
        )
            );

$config['order/order_remark/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_id',
            'label' => '订单V',
            'rules' => 'trim|required|numeric|max_length[11]'
        ),
                                array (
            'field' => 'for',
            'label' => '内部备注',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|required|max_length[1024]'
        ),
                                array (
            'field' => 'status',
            'label' => '订单状态',
            'rules' => 'trim|numeric|max_length[4]'
        )
            );

$config['order/order_remark/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                    );
