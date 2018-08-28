<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_order_product_board_msg/add'] = array(
                        array (
            'field' => 'order_product_board_id',
            'label' => '订单产品板块V',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建时间',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'msg',
            'label' => 'Message',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'workflow_order_product_board_id',
            'label' => '工作流',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'previous',
            'label' => '前置节点',
            'rules' => 'trim|numeric|max_length[4]'
        )
            );

$config['workflow/workflow_order_product_board_msg/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_product_board_id',
            'label' => '订单产品板块V',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建时间',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'msg',
            'label' => 'Message',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'workflow_order_product_board_id',
            'label' => '工作流',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'previous',
            'label' => '前置节点',
            'rules' => 'trim|numeric|max_length[4]'
        )
            );

$config['workflow/workflow_order_product_board_msg/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        )
                            );
