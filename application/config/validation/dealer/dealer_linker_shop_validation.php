<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_linker_shop/add'] = array(
                        array (
            'field' => 'dealer_linker_id',
            'label' => '经销商联系人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'shop_id',
            'label' => '店面',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'primary',
            'label' => '首要联系人',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['dealer/dealer_linker_shop/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'dealer_linker_id',
            'label' => '经销商联系人',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'shop_id',
            'label' => '店面',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'primary',
            'label' => '首要联系人',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['dealer/dealer_linker_shop/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                );
