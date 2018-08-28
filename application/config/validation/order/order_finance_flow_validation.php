<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_finance_flow/add'] = array(
                        array (
            'field' => 'order_id',
            'label' => '订单',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'payed_money',
            'label' => '支付金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'status',
            'label' => '支付状态',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'order_status',
            'label' => '订单状态',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'finance_flow_type',
            'label' => '流水类型',
            'rules' => 'trim|in_list[首付,尾款,白条还款,宽松政策补款,全款]'
        )
            );

$config['order/order_finance_flow/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_id',
            'label' => '订单',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'payed_money',
            'label' => '支付金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'status',
            'label' => '支付状态',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'order_status',
            'label' => '订单状态',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'finance_flow_type',
            'label' => '流水类型',
            'rules' => 'trim|in_list[首付,尾款,白条还款,宽松政策补款,全款]'
        )
            );

$config['order/order_finance_flow/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                        );
