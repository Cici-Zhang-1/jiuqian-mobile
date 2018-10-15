<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/punch_model/_select_order_product_classify'] = array(
    0 => 'type',
    'opc_id' => 'v',
    'opc_board' => 'board',
    'u_truename' => 'punch',
    'ifnull(opc_edge_datetime, ifnull(opc_saw_datetime, opc_print_datetime))' => 'sort_datetime',
    'opc_punch_datetime' => 'punch_datetime',
    'sum(opc_amount)' => 'amount',
    'sum(opc_area)' => 'area',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'p_name' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type'
);
$config['order/punch_model/_select_order_product_board'] = array(
    1 => 'type',
    'opb_id' => 'v',
    'opb_board' => 'board',
    'u_truename' => 'punch',
    'ifnull(opb_edge_datetime, ifnull(opb_saw_datetime, opb_print_datetime))' => 'sort_datetime',
    'opb_punch_datetime' => 'punch_datetime',
    'sum(opb_amount)' => 'amount',
    'sum(opb_area)' => 'area',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'p_name' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type'
);
