<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['manage/configs/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'label',
            'label' => 'label',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'config',
            'label' => 'config',
            'rules' => 'trim|max_length[64]'
        )
            );

$config['manage/configs/edit'] = array(
                    array(
            'field' => 'selected',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'name',
            'label' => 'name',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'label',
            'label' => 'label',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'config',
            'label' => 'config',
            'rules' => 'trim|max_length[64]'
        )
            );

$config['manage/configs/remove'] = array(
            array(
            'field' => 'selected[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[4]'
        )
                );
