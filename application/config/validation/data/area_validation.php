<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/area/add'] = array(
                        array (
            'field' => 'province',
            'label' => '省',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'city',
            'label' => '市',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'county',
            'label' => '县',
            'rules' => 'trim|max_length[64]'
        )
            );

$config['data/area/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[6]'
        ),
                                array (
            'field' => 'province',
            'label' => '省',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'city',
            'label' => '市',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'county',
            'label' => '县',
            'rules' => 'trim|max_length[64]'
        )
            );

$config['data/area/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[6]'
        )
                );
