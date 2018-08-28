<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/finance_activity_type/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[16]|is_unique[finance_activity_type.fat_name]'
        )
            );

$config['data/finance_activity_type/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[16]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[16]|is_unique[finance_activity_type.fat_name]'
        )
            );

$config['data/finance_activity_type/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[16]'
        )
        );
