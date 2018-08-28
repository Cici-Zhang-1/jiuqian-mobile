<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/procedure/add'] = array(
                        array (
            'field' => 'id',
            'label' => 'v',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]|is_unique[procedure.p_id]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[32]'
        )
            );

$config['data/procedure/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'id',
            'label' => 'v',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        ),
                                array (
            'field' => 'label',
            'label' => 'Label',
            'rules' => 'trim|required|max_length[32]'
        )
            );

$config['data/procedure/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
        )
            );
