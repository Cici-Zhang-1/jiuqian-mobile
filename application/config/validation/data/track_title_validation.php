<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/track_title/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[128]|is_unique[track_title.tt_name]'
        )
            );

$config['data/track_title/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[128]'
        )
            );

$config['data/track_title/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[128]'
        )
        );
