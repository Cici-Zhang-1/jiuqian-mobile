<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/application_status/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[1]|is_unique[application_status.as_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[16]'
        )
            );

$config['data/application_status/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[1]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[1]|is_unique[application_status.as_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[16]'
        )
            );

$config['data/application_status/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[1]'
        )
            );
