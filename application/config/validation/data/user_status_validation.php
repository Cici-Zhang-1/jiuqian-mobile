<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/user_status/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[2]|is_unique[user_status.us_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[32]'
        )
            );

$config['data/user_status/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[2]|is_unique[user_status.us_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[32]'
        )
            );

$config['data/user_status/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
        )
            );
