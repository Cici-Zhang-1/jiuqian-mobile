<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['supplier/supplier/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|is_unique[supplier.s_name]|max_length[64]'
        ),
                                array (
            'field' => 'code',
            'label' => '编码代号',
            'rules' => 'trim|alpha_numeric|max_length[8]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['supplier/supplier/edit'] = array(
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
            'field' => 'code',
            'label' => '编码代号',
            'rules' => 'trim|alpha_numeric|max_length[8]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['supplier/supplier/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        )
                );
