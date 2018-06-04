<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|is_unique[role.r_name]|max_length[64]'
        ),
                                array (
            'field' => 'creator',
            'label' => 'creator',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => 'create_datetime',
            'rules' => 'trim|'
        )
            );

$config['permission/role/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|is_unique[role.r_id]|required|numeric|max_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|is_unique[role.r_name]|max_length[64]'
        ),
                                array (
            'field' => 'creator',
            'label' => 'creator',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => 'create_datetime',
            'rules' => 'trim|'
        )
            );

$config['permission/role/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|is_unique[role.r_id]|required|numeric|max_length[1]|max_length[4]'
        )
                );
