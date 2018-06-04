<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/outted_sheet/add'] = array(
                        array (
            'field' => 'num',
            'label' => 'num',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'pick_sheet_id',
            'label' => 'pick_sheet_id',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'amount',
            'label' => 'amount',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'collection',
            'label' => 'collection',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'creator',
            'label' => 'creator',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => 'create_datetime',
            'rules' => 'trim|'
        )
            );

$config['warehouse/outted_sheet/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'num',
            'label' => 'num',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'pick_sheet_id',
            'label' => 'pick_sheet_id',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'amount',
            'label' => 'amount',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'collection',
            'label' => 'collection',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'creator',
            'label' => 'creator',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'create_datetime',
            'label' => 'create_datetime',
            'rules' => 'trim|'
        )
            );

$config['warehouse/outted_sheet/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|max_length[1]|max_length[10]'
        )
                            );
