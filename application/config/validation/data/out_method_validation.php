<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/out_method/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[64]|is_unique[out_method.om_name]'
        )
            );

$config['data/out_method/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[64]|is_unique[out_method.om_name]'
        )
            );

$config['data/out_method/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[64]'
        )
        );
