<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product/add'] = array(
        array (
            'field' => 'order_id',
            'label' => '订单',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
        array (
            'field' => 'product_id',
            'label' => '产品',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
        array (
            'field' => 'set',
            'label' => '套数',
            'rules' => 'trim|required|numeric|greater_than[0]|less_than_equal_to[20]'
        ),
        array (
            'field' => 'product',
            'label' => '产品',
            'rules' => 'trim|max_length[64]'
        ),
        array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        )
            );

$config['order/order_product/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'order_id',
            'label' => '订单',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'product_id',
            'label' => '产品',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'product',
            'label' => '产品',
            'rules' => 'trim|max_length[64]'
        ),
                                array (
            'field' => 'num',
            'label' => '订单产品编号',
            'rules' => 'trim|required|is_unique[order_product.op_num]|max_length[64]'
        ),
                                array (
            'field' => 'sum',
            'label' => '金额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
                                array (
            'field' => 'sum_diff',
            'label' => '金额差额',
            'rules' => 'trim|decimal|min_length[1]'
        ),
    array (
        'field' => 'virtual_diff',
        'label' => '虚拟金额',
        'rules' => 'trim|decimal|min_length[1]'
    ),
                                array (
            'field' => 'pack',
            'label' => '包装',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'pack_detail',
            'label' => '包装详情',
            'rules' => 'trim|max_length[128]'
        ),
                                array (
            'field' => 'status',
            'label' => '状态',
            'rules' => 'trim|numeric|max_length[4]'
        ),
                                array (
            'field' => 'remark',
            'label' => '备注',
            'rules' => 'trim|max_length[128]'
        ),
    array (
        'field' => 'design_atlas',
        'label' => '设计图集',
        'rules' => 'trim|max_length[64]'
    )
            );

$config['order/order_product/remove'] = array(
            array(
            'field' => 'v[]',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
);

$config['order/order_product/repeat'] = array(
    array(
        'field' => 'v',
        'label' => '待拷贝项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array (
        'field' => 'set',
        'label' => '套数',
        'rules' => 'trim|required|numeric|greater_than[0]|less_than_equal_to[5]'
    )
);

$config['order/order_product/repeat_to'] = array(
    array(
        'field' => 'v',
        'label' => '待复制项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array (
        'field' => 'to',
        'label' => '复制到',
        'rules' => 'trim|required|max_length[64]'
    )
);

$config['order/order_product/design_atlas'] = array(
    array(
        'field' => 'v',
        'label' => '编号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array (
        'field' => 'design_atlas',
        'label' => '设计图集',
        'rules' => 'trim|required|max_length[64]'
    )
);
