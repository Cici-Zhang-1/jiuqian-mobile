<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/saler_board_price/add'] = array(
                        array (
            'field' => 'board[]',
            'label' => '板材',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'unit_price',
            'label' => '定价',
            'rules' => 'trim|required|max_length[10]'
        )
            );

$config['product/saler_board_price/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'board',
            'label' => '板材',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'unit_price',
            'label' => '定价',
            'rules' => 'trim|required|max_length[10]'
        )
            );

$config['product/saler_board_price/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
            );
