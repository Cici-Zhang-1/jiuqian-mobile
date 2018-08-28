<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|required|is_unique[role.r_name]|max_length[64]'
        )
            );

$config['permission/role/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '角色编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|required|is_unique[role.r_name]|max_length[64]'
        )
            );

$config['permission/role/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        )
                );
