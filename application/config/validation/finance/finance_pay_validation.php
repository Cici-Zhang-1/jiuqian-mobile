<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_pay/add'] = array(
                        array (
            'field' => 'finance_account_id',
            'label' => '支出账号#',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'finance_account',
            'label' => '支付账号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'in_finance_account_id',
            'label' => '转入账号#',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'in_finance_account',
            'label' => '转入账号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'income_pay',
            'label' => '支出类型',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'flow_num',
            'label' => '流水号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'amount',
            'label' => '支出金额',
            'rules' => 'trim|required|decimal|min_length[1]'
        ),
                                array (
            'field' => 'fee',
            'label' => '支出手续费',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'supplier_id',
            'label' => '供应商',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'supplier',
            'label' => '供应商',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'bank_date',
            'label' => '支出日期',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['finance/finance_pay/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'finance_account_id',
            'label' => '支出账号#',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'finance_account',
            'label' => '支付账号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'in_finance_account_id',
            'label' => '转入账号#',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'in_finance_account',
            'label' => '转入账号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'income_pay',
            'label' => '支出类型',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'flow_num',
            'label' => '流水号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'amount',
            'label' => '支出金额',
            'rules' => 'trim|required|decimal|min_length[1]'
        ),
                                array (
            'field' => 'fee',
            'label' => '支出手续费',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'supplier_id',
            'label' => '供应商',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'supplier',
            'label' => '供应商',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'bank_date',
            'label' => '支出日期',
            'rules' => 'trim|required'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['finance/finance_pay/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                                        );
