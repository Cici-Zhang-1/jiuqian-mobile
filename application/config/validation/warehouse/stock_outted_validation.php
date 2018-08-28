<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/stock_outted/add'] = array(
                        array (
            'field' => 'num',
            'label' => '提货单编号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'truck',
            'label' => '车',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'train',
            'label' => '车次',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'end_datetime',
            'label' => '出厂日期',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'amount',
            'label' => '数量',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'logistics',
            'label' => '物流',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'delivered_amount',
            'label' => '实发件数',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'collection',
            'label' => '代收金额',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'printer',
            'label' => '打印员',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'print_datetime',
            'label' => '打印时间',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建时间',
            'rules' => 'trim|'
        )
            );

$config['warehouse/stock_outted/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'num',
            'label' => '提货单编号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'truck',
            'label' => '车',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'train',
            'label' => '车次',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'end_datetime',
            'label' => '出厂日期',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'amount',
            'label' => '数量',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'logistics',
            'label' => '物流',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'delivered_amount',
            'label' => '实发件数',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'collection',
            'label' => '代收金额',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'printer',
            'label' => '打印员',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'print_datetime',
            'label' => '打印时间',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'creator',
            'label' => '创建人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => '创建时间',
            'rules' => 'trim|'
        )
            );

$config['warehouse/stock_outted/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        )
                                                    );
