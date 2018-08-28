<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/order_type/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'V',
            'rules' => 'trim|required|max_length[2]|is_unique[order_type.ot_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[16]'
        )
            );

$config['data/order_type/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[2]'
        ),
                                array (
            'field' => 'name',
            'label' => 'V',
            'rules' => 'trim|required|max_length[2]|is_unique[order_type.ot_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[16]'
        )
            );

$config['data/order_type/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[2]'
        )
            );
