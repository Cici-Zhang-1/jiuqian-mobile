<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/hinge_punch/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[64]|is_unique[hinge_punch.hp_name]'
        )
            );

$config['data/hinge_punch/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|max_length[64]|is_unique[hinge_punch.hp_name]'
        )
            );

$config['data/hinge_punch/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[64]'
        )
        );
