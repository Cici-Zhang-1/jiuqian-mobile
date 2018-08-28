<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/board_class/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[4]|is_unique[board_class.bc_name]'
        )
            );

$config['data/board_class/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[4]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[4]'
        )
            );

$config['data/board_class/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[4]'
        )
        );
