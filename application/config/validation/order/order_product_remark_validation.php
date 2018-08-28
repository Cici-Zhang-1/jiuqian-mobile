<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_remark/add'] = array(
                        array (
            'field' => 'order_product_id',
            'label' => '订单产品编号',
            'rules' => 'trim|required|numeric|max_length[11]'
        ),
                                array (
            'field' => 'status',
            'label' => '工作流',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|required|max_length[128]'
        )
            );

$config['order/order_product_remark/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_product_id',
            'label' => '订单产品编号',
            'rules' => 'trim|required|numeric|max_length[11]'
        ),
                                array (
            'field' => 'status',
            'label' => '工作流',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|required|max_length[128]'
        )
            );

$config['order/order_product_remark/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                );
