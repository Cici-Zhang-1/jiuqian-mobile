<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/stock_outted_status/add'] = array(
                        array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[2]|is_unique[stock_outted_status.sos_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[32]'
        )
            );

$config['data/stock_outted_status/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[2]'
        ),
                                array (
            'field' => 'name',
            'label' => 'v',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[2]|is_unique[stock_outted_status.sos_name]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[32]'
        )
            );

$config['data/stock_outted_status/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[2]'
        )
            );
