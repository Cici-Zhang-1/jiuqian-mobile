<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/production_line/add'] = array(
                        array (
            'field' => 'id',
            'label' => '生产线',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]|is_unique[production_line.pl_id]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[32]'
        ),
    array (
        'field' => 'num',
        'label' => '序列号',
        'rules' => 'trim|max_length[64]'
    )
            );

$config['data/production_line/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'id',
            'label' => '生产线',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[32]'
        ),
    array (
        'field' => 'num',
        'label' => '序列号',
        'rules' => 'trim|max_length[64]'
    )
            );

$config['data/production_line/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        )
            );
