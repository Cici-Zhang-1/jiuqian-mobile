<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/order_stock_outted/add'] = array(
                        array (
            'field' => 'id',
            'label' => 'v',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]|is_unique[order_stock_outted.oso_id]'
        ),
                                array (
            'field' => 'order_id',
            'label' => '订单V',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'stock_outted_id',
            'label' => '拣货单V',
            'rules' => 'trim|required|numeric|max_length[10]'
        )
            );

$config['warehouse/order_stock_outted/edit'] = array(
                    array(
            'field' => 'v',
            'label' => '编号',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        ),
                                array (
            'field' => 'id',
            'label' => 'v',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]|is_unique[order_stock_outted.oso_id]'
        ),
                                array (
            'field' => 'order_id',
            'label' => '订单V',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'stock_outted_id',
            'label' => '拣货单V',
            'rules' => 'trim|required|numeric|max_length[10]'
        )
            );

$config['warehouse/order_stock_outted/remove'] = array(
            array(
            'field' => 'v',
            'label' => '选择项',
            'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
        )
                );
