<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/board/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'length',
            'label' => '长',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'width',
            'label' => '宽',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'thick[]',
            'label' => '厚',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'color[]',
            'label' => '颜色',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'nature[]',
            'label' => '材质',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'class',
            'label' => '等级',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'purchase',
            'label' => '采购价格',
            'rules' => 'trim|decimal'
        ),
                                array (
            'field' => 'unit_price',
            'label' => '单价',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'amount',
            'label' => '数量',
            'rules' => 'trim|numeric'
        ),
                                array (
            'field' => 'supplier_id',
            'label' => '供应商',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['product/board/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'length',
            'label' => '长',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'width',
            'label' => '宽',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'thick',
            'label' => '厚',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'color',
            'label' => '颜色',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'nature',
            'label' => '材质',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'class',
            'label' => '等级',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'purchase',
            'label' => '采购价格',
            'rules' => 'trim|decimal'
        ),
                                array (
            'field' => 'unit_price',
            'label' => '单价',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'amount',
            'label' => '数量',
            'rules' => 'trim|numeric'
        ),
                                array (
            'field' => 'supplier_id',
            'label' => '供应商',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[1]'
        )
            );

$config['product/board/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|max_length[64]'
        )
                                                        );

$config['product/board/start'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|max_length[64]'
    )
);

$config['product/board/stop'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|max_length[64]'
    )
);

$config['product/board/purchase'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|max_length[64]'
    ),
    array (
        'field' => 'purchase',
        'label' => '采购价格',
        'rules' => 'trim|required|decimal'
    )
);
