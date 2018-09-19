<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_order_product_fitting_msg/add'] = array(
                        array (
            'field' => 'order_product_fitting_id',
            'label' => '配件id',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'msg',
            'label' => 'Message',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'workflow_order_product_fitting_id',
            'label' => '工作流',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'previous',
            'label' => '前置节点',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'target',
            'label' => '操作对象',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'timestamp',
            'label' => '排序',
            'rules' => 'trim|decimal|min_length[1]|max_length[20]'
        ),
                                array (
            'field' => 'status',
            'label' => '是否有效',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['workflow/workflow_order_product_fitting_msg/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_product_fitting_id',
            'label' => '配件id',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'msg',
            'label' => 'Message',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'workflow_order_product_fitting_id',
            'label' => '工作流',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'previous',
            'label' => '前置节点',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'target',
            'label' => '操作对象',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'timestamp',
            'label' => '排序',
            'rules' => 'trim|decimal|min_length[1]|max_length[20]'
        ),
                                array (
            'field' => 'status',
            'label' => '是否有效',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['workflow/workflow_order_product_fitting_msg/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                );
