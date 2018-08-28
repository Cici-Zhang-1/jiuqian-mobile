<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_balance/add'] = array(
                        array (
            'field' => 'dealer_id',
            'label' => '经销商',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'start',
            'label' => '期初金额',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'end',
            'label' => '期末金额',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'start_date',
            'label' => '开始日期',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'end_date',
            'label' => '截止日期',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'asured',
            'label' => '本期应收',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'received',
            'label' => '本期实收',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'orders',
            'label' => '期间订单',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'finance_received',
            'label' => '期间收款单',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'difference',
            'label' => '期间应收差额',
            'rules' => 'trim|required|max_length[10]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建日期',
            'rules' => 'trim|required'
        )
            );

$config['dealer/dealer_balance/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'dealer_id',
            'label' => '经销商',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'start',
            'label' => '期初金额',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'end',
            'label' => '期末金额',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'start_date',
            'label' => '开始日期',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'end_date',
            'label' => '截止日期',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'asured',
            'label' => '本期应收',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'received',
            'label' => '本期实收',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'orders',
            'label' => '期间订单',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'finance_received',
            'label' => '期间收款单',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'difference',
            'label' => '期间应收差额',
            'rules' => 'trim|required|max_length[10]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建日期',
            'rules' => 'trim|required'
        )
            );

$config['dealer/dealer_balance/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                                        );
