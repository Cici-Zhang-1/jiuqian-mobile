<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_income/add'] = array(
                        array (
            'field' => 'finance_account_id',
            'label' => '账号',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'income_pay',
            'label' => '收入类型',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'flow_num',
            'label' => '流水号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'cargo_no',
            'label' => '货号',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'amount',
            'label' => '金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'fee',
            'label' => '手续费',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'dealer_id',
            'label' => '经销商',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'dealer',
            'label' => '经销商',
            'rules' => 'trim|max_length[512]'
        ),
                                array (
            'field' => 'corresponding',
            'label' => '货款金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'bank_date',
            'label' => '到账日期',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[256]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'inned',
            'label' => '未到账款',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['finance/finance_income/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'finance_account_id',
            'label' => '账号',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'income_pay',
            'label' => '收入类型',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'flow_num',
            'label' => '流水号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'cargo_no',
            'label' => '货号',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'amount',
            'label' => '金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'fee',
            'label' => '手续费',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'dealer_id',
            'label' => '经销商',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'dealer',
            'label' => '经销商',
            'rules' => 'trim|max_length[512]'
        ),
                                array (
            'field' => 'corresponding',
            'label' => '货款金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'bank_date',
            'label' => '到账日期',
            'rules' => 'trim|'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[256]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'inned',
            'label' => '未到账款',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['finance/finance_income/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                                        );
