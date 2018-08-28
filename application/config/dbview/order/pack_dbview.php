<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/pack_model/_select_order_product_classify'] = array(
    'opc_id' => 'v',
    'opc_area' => 'area',
    'opc_board' => 'board',
    'u_truename' => 'pack',
    'opc_pack_datetime' => 'pack_datetime',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'c_name' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type',
    0 => 'type'
);
$config['order/pack_model/_select_order_product_board'] = array(
    1 => 'type',
    'opb_id' => 'v',
    'sum(opb_area)' => 'area',
    'opb_board' => 'board',
    'u_truename' => 'pack',
    'opb_pack_datetime' => 'pack_datetime',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'p_name' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type'
);
