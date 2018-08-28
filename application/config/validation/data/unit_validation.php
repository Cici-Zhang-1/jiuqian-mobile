<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/unit/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[16]|is_unique[unit.u_name]'
        )
            );

$config['data/unit/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[16]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[16]'
        )
            );

$config['data/unit/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[16]'
        )
        );
