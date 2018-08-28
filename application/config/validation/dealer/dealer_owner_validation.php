<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_owner/add'] = array(
                        array (
            'field' => 'dealer_id',
            'label' => '经销商',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'owner_id',
            'label' => '客服',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'primary',
            'label' => '首要',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['dealer/dealer_owner/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'dealer_id',
            'label' => '经销商',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'owner_id',
            'label' => '客服',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'primary',
            'label' => '首要',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['dealer/dealer_owner/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                );

$config['dealer/dealer_owner/claim'] = array(
    array(
        'field' => 'v[]',
        'label' => '经销商',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);

$config['dealer/dealer_owner/discard'] = array(
    array(
        'field' => 'v[]',
        'label' => '经销商',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);

$config['dealer/dealer_owner/release'] = array(
    array(
        'field' => 'v[]',
        'label' => '经销商',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
