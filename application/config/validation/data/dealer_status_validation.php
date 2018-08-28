<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/dealer_status/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[2]|is_unique[dealer_status.ds_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[64]'
        )
            );

$config['data/dealer_status/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[2]|is_unique[dealer_status.ds_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[64]'
        )
            );

$config['data/dealer_status/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
        )
            );
