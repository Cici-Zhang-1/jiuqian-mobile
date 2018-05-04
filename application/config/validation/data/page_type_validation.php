<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/page_type/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'label',
            'label' => 'label',
            'rules' => 'trim|max_length[16]'
        )
            );

$config['data/page_type/edit'] = array(
                    array(
            'field' => 'selected',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'label',
            'label' => 'label',
            'rules' => 'trim|max_length[16]'
        )
            );

$config['data/page_type/remove'] = array(
            array(
            'field' => 'selected[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[4]'
        )
            );
