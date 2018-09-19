<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_account_book/add'] = array(
                        array (
            'field' => 'flow_num',
            'label' => '流水号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'dealer_id',
            'label' => '客户id',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'in',
            'label' => '进账',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'amount',
            'label' => '金额',
            'rules' => 'trim|decimal|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'title',
            'label' => '主题',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'category',
            'label' => '类别',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'source_id',
            'label' => '源ID',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[256]'
        ),
                                array (
            'field' => 'balance',
            'label' => '余额',
            'rules' => 'trim|required|decimal|min_length[1]|max_length[10]'
        )
            );

$config['dealer/dealer_account_book/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[256]'
        )
            );

$config['dealer/dealer_account_book/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                        );
