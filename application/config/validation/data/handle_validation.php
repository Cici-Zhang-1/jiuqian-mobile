<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/handle/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[64]|is_unique[handle.h_name]'
        ),
                                array (
            'field' => 'open_hole',
            'label' => '是否打孔',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'invisibility',
            'label' => '隐形拉手',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['data/handle/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[64]|is_unique[handle.h_name]'
        ),
                                array (
            'field' => 'open_hole',
            'label' => '是否打孔',
            'rules' => 'trim|numeric|max_length[1]'
        ),
                                array (
            'field' => 'invisibility',
            'label' => '隐形拉手',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['data/handle/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[64]'
        )
                );
