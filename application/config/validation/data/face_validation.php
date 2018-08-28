<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/face/add'] = array(
                        array (
            'field' => 'slot',
            'label' => '开槽',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'punch',
            'label' => '打孔',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'flag',
            'label' => '标志',
            'rules' => 'trim|required|max_length[128]'
        )
            );

$config['data/face/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'slot',
            'label' => '开槽',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'punch',
            'label' => '打孔',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'flag',
            'label' => '标志',
            'rules' => 'trim|required|max_length[128]'
        )
            );

$config['data/face/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        )
                );
