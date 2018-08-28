<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/income_pay/add'] = array(
                    array (
            'field' => 'finance_activity_type',
            'label' => '收支类型',
            'rules' => 'trim|required|max_length[16]'
        ),
                                    array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]|is_unique[income_pay.ip_name]'
        )
            );

$config['finance/income_pay/edit'] = array(
                    array (
            'field' => 'finance_activity_type',
            'label' => '收支类型',
            'rules' => 'trim|required|max_length[16]'
        ),
                                array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]|is_unique[income_pay.ip_name]'
        )
            );

$config['finance/income_pay/remove'] = array(
                array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[64]'
        )
        );
