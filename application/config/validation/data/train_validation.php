<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/train/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]|is_unique[train.t_name]'
        )
            );

$config['data/train/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]|is_unique[train.t_name]'
        )
            );

$config['data/train/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[64]'
        )
        );
