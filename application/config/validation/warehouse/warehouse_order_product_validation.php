<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse_order_product/in'] = array(
                        array (
            'field' => 'warehouse_v[]',
            'label' => '库位编号',
            'rules' => 'trim|min_length[1]|max_length[32]'
        ),
    array (
        'field' => 'warehouse_v_hand[]',
        'label' => '手动选择库位',
        'rules' => 'trim|min_length[1]|max_length[32]'
    ),
                                array (
            'field' => 'order_product_num',
            'label' => '订单产品',
            'rules' => 'trim|required|max_length[64]'
        )
            );

$config['warehouse/warehouse_order_product/out'] = array(
                    array(
            'field' => 'v[]',
            'label' => '库位订单编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
            );

$config['warehouse/warehouse_order_product/move'] = array(
            array(
            'field' => 'v[]',
            'label' => '移出库位订单产品编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
     array (
         'field' => 'to',
         'label' => '移入仓库',
         'rules' => 'trim|required|max_length[32]'
     )
);
