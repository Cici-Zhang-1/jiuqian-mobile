<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_account/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|is_unique[finance_account.fa_name]|max_length[64]'
        ),
                                array (
            'field' => 'host',
            'label' => '户主',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'account',
            'label' => '账号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'intime',
            'label' => '及时进账',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'balance',
            'label' => '账户余额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'in',
            'label' => '收入金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'in_fee',
            'label' => '收入手续费',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'out',
            'label' => '支出金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'out_fee',
            'label' => '支出手续费',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'fee',
            'label' => '费率',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'fee_max',
            'label' => '最高手续费',
            'rules' => 'trim|decimal|min_length[1]'
        )
            );

$config['finance/finance_account/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'host',
            'label' => '户主',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'account',
            'label' => '账号',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'intime',
            'label' => '及时进账',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'balance',
            'label' => '账户余额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'in',
            'label' => '收入金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'in_fee',
            'label' => '收入手续费',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'out',
            'label' => '支出金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'out_fee',
            'label' => '支出手续费',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'fee',
            'label' => '费率',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'fee_max',
            'label' => '最高手续费',
            'rules' => 'trim|decimal|min_length[1]'
        )
            );

$config['finance/finance_account/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        )
                                                );
