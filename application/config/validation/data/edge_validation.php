<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/edge/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]|is_unique[edge.e_name]'
        ),
    array(
        'field' => 'thick',
        'label' => '厚度',
        'rules' => 'trim|numeric|max_length[4]'
    ),
                                array (
            'field' => 'ups',
            'label' => '上',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'downs',
            'label' => '下',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'lefts',
            'label' => '左',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'rights',
            'label' => '右',
            'rules' => 'trim|decimal|min_length[1]'
        )
            );

$config['data/edge/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]|is_unique[edge.e_name]'
        ),
    array(
        'field' => 'thick',
        'label' => '厚度',
        'rules' => 'trim|numeric|max_length[4]'
    ),
                                array (
            'field' => 'ups',
            'label' => '上',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'downs',
            'label' => '下',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'lefts',
            'label' => '左',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'rights',
            'label' => '右',
            'rules' => 'trim|decimal|min_length[1]'
        )
            );

$config['data/edge/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[64]'
        )
                        );
