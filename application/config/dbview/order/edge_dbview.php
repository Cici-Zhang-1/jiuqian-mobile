<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/edge_model/_select_order_product_classify'] = array(
    0 => 'type',
    'opc_id' => 'v',
    'opc_board' => 'board',
    'u_truename' => 'edge',
    'opc_edge_datetime' => 'edge_datetime',
    'sum(opc_area)' => 'area',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'p_name' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type'
);
$config['order/edge_model/_select_order_product_board'] = array(
    1 => 'type',
    'opb_id' => 'v',
    'opb_board' => 'board',
    'u_truename' => 'edge',
    'opb_edge_datetime' => 'edge_datetime',
    'sum(opb_area)' => 'area',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'p_name' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type'
);
