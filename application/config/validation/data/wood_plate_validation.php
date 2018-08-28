<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/wood_plate/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[32]|is_unique[wood_plate.wp_name]'
        ),
                                array (
            'field' => 'core',
            'label' => '门芯',
            'rules' => 'trim|required|max_length[16]'
        )
            );

$config['data/wood_plate/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[32]|is_unique[wood_plate.wp_name]'
        ),
                                array (
            'field' => 'core',
            'label' => '门芯',
            'rules' => 'trim|required|max_length[16]'
        )
            );

$config['data/wood_plate/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[32]'
        )
            );
