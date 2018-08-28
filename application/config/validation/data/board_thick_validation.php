<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/board_thick/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]|is_unique[board_thick.bt_name]'
        ),
                                array (
            'field' => 'thicker',
            'label' => '是否是厚板',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['data/board_thick/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'thicker',
            'label' => '是否是厚板',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['data/board_thick/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
            );
