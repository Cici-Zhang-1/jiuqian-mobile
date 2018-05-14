<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['test/test/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'phone',
            'label' => 'phone',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'remark',
            'label' => 'remark',
            'rules' => 'trim|'
        )
            );

$config['test/test/edit'] = array(
                    array(
            'field' => 'selected',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'phone',
            'label' => 'phone',
            'rules' => 'trim|max_length[32]'
        ),
                                array (
            'field' => 'remark',
            'label' => 'remark',
            'rules' => 'trim|'
        )
            );

$config['test/test/remove'] = array(
            array(
            'field' => 'selected[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        )
                );
