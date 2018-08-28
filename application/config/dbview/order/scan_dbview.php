<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/scan_model/_select_order_product_classify'] = array(
    0 => 'type',
    'opc_id' => 'v',
    'opc_board' => 'board',
    'u_truename' => 'scan',
    'opc_scan_datetime' => 'scan_datetime',
    'opc_area' => 'area',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'c_name' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type'
);
$config['order/scan_model/_select_order_product_board'] = array(
    1 => 'type',
    'opb_id' => 'v',
    'opb_board' => 'board',
    'u_truename' => 'scan',
    'opb_scan_datetime' => 'scan_datetime',
    'sum(opb_area)' => 'area',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'op_product' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type'
);
