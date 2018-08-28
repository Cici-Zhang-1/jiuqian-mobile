<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/saler_goods_price/add'] = array(
                        array (
            'field' => 'goods_speci_id[]',
            'label' => '商品规格',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'unit_price',
            'label' => '定价',
            'rules' => 'trim|max_length[10]'
        )
            );

$config['product/saler_goods_price/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'goods_speci_id',
            'label' => '商品规格',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'unit_price',
            'label' => '定价',
            'rules' => 'trim|max_length[10]'
        )
            );

$config['product/saler_goods_price/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
            );
