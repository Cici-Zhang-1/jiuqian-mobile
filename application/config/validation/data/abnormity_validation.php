<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/abnormity/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]|is_unique[abnormity.a_name]'
        ),
                                array (
            'field' => 'print_list',
            'label' => '打印',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'ScanPlate',
            'label' => '扫描',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['data/abnormity/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]|is_unique[abnormity.a_name]'
        ),
                                array (
            'field' => 'print_list',
            'label' => '打印',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'ScanPlate',
            'label' => '扫描',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['data/abnormity/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[64]'
        )
                );
