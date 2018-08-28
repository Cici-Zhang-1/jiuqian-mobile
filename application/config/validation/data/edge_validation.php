<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/edge/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]|is_unique[edge.e_name]'
        ),
                                array (
            'field' => 'up',
            'label' => '上',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'down',
            'label' => '下',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'left',
            'label' => '左',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'right',
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
                                array (
            'field' => 'up',
            'label' => '上',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'down',
            'label' => '下',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'left',
            'label' => '左',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'right',
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
