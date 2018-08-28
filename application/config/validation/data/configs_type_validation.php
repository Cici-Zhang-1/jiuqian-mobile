<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/configs_type/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[32]|is_unique[configs_type.ct_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[32]'
        )
            );

$config['data/configs_type/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[32]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[32]|is_unique[configs_type.ct_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[32]'
        )
            );

$config['data/configs_type/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[32]'
        )
            );
