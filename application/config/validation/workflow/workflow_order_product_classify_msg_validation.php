<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_order_product_classify_msg/add'] = array(
                        array (
            'field' => 'order_product_classify_id',
            'label' => '订单产品板块分类V',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'msg',
            'label' => 'Message',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'workflow_order_product_classify_id',
            'label' => '当前工作流',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'previous',
            'label' => '前一工作流',
            'rules' => 'trim|numeric|max_length[4]'
        )
            );

$config['workflow/workflow_order_product_classify_msg/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_product_classify_id',
            'label' => '订单产品板块分类V',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'msg',
            'label' => 'Message',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'workflow_order_product_classify_id',
            'label' => '当前工作流',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'previous',
            'label' => '前一工作流',
            'rules' => 'trim|numeric|max_length[4]'
        )
            );

$config['workflow/workflow_order_product_classify_msg/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                    );
