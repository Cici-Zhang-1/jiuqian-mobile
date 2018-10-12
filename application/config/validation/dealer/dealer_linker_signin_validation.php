<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_linker_signin/add'] = array(
                        array (
            'field' => 'dealer_linker_id',
            'label' => '客户联系人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'ip',
            'label' => '登录IP',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'host',
            'label' => '登录主机',
            'rules' => 'trim|required|max_length[128]'
        )
            );

$config['dealer/dealer_linker_signin/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'dealer_linker_id',
            'label' => '客户联系人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'ip',
            'label' => '登录IP',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'host',
            'label' => '登录主机',
            'rules' => 'trim|required|max_length[128]'
        )
            );

$config['dealer/dealer_linker_signin/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                );
