<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/plate/add'] = array(
                        array (
            'field' => 'name',
            'label' => '板块',
            'rules' => 'trim|required|max_length[64]|is_unique[plate.p_name]'
        )
            );

$config['data/plate/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => '板块',
            'rules' => 'trim|required|max_length[64]|is_unique[plate.p_name]'
        )
            );

$config['data/plate/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[64]'
        )
        );
