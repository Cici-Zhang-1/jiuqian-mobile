<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/goods/add'] = array(
                        array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'product_id',
            'label' => '分类',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'code',
            'label' => '编码',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'purchase_unit',
            'label' => '采购单位',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'unit',
            'label' => '销售单位',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'purchase',
            'label' => '采购单价',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'unit_price',
            'label' => '销售单价',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'supplier_id',
            'label' => '供应商',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'speci[]',
            'label' => '规格',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[1024]'
        )
            );

$config['product/goods/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'name',
            'label' => '名称',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'product_id',
            'label' => '分类',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'code',
            'label' => '编码',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'purchase_unit',
            'label' => '采购单位',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'unit',
            'label' => '销售单位',
            'rules' => 'trim|max_length[16]'
        ),
                                array (
            'field' => 'purchase',
            'label' => '采购单价',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'unit_price',
            'label' => '销售单价',
            'rules' => 'trim|max_length[10]'
        ),
                                array (
            'field' => 'supplier_id',
            'label' => '供应商',
            'rules' => 'trim|required|numeric|max_length[4]'
        ),
                                array (
            'field' => 'speci[]',
            'label' => '规格',
            'rules' => 'trim|numeric|max_length[10]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[1024]'
        )
            );

$config['product/goods/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                                                );

$config['product/goods/stop'] = array(
    array(
        'field' => 'v[]',
        'label' => '停售项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);

$config['product/goods/start'] = array(
    array(
        'field' => 'v[]',
        'label' => '起售项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
