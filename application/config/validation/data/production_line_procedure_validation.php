<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/production_line_procedure/add'] = array(
                        array (
            'field' => 'production_line',
            'label' => '生产线',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'procedure',
            'label' => '工序',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'displayorder',
            'label' => '执行顺序',
            'rules' => 'trim|numeric|max_length[4]'
        )
            );

$config['data/production_line_procedure/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'production_line',
            'label' => '生产线',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'procedure',
            'label' => '工序',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'displayorder',
            'label' => '执行顺序',
            'rules' => 'trim|numeric|max_length[4]'
        )
            );

$config['data/production_line_procedure/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                );
