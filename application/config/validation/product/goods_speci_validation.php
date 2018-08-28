<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/goods_speci/add'] = array(
                        array (
            'field' => 'goods_id',
            'label' => '商品v',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'speci',
            'label' => '规格分类',
            'rules' => 'trim|required|max_length[1024]'
        ),
                                array (
            'field' => 'code',
            'label' => '编码',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'purchase',
            'label' => '采购单价',
            'rules' => 'trim|required|max_length[10]'
        ),
                                array (
            'field' => 'unit_price',
            'label' => '销售价格',
            'rules' => 'trim|required|max_length[10]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[1024]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['product/goods_speci/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'goods_id',
            'label' => '商品v',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'speci',
            'label' => '规格分类',
            'rules' => 'trim|required|max_length[1024]'
        ),
                                array (
            'field' => 'code',
            'label' => '编码',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'purchase',
            'label' => '采购单价',
            'rules' => 'trim|required|max_length[10]'
        ),
                                array (
            'field' => 'unit_price',
            'label' => '销售价格',
            'rules' => 'trim|required|max_length[10]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[1024]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['product/goods_speci/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                );

$config['product/goods_speci/stop'] = array(
    array(
        'field' => 'v[]',
        'label' => '停售项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);

$config['product/goods_speci/start'] = array(
    array(
        'field' => 'v[]',
        'label' => '起售项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
