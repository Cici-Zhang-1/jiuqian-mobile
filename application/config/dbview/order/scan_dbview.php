<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/scan_model/_select_order_product_classify'] = array(
    'opc_id' => 'v',
    // 'opc_area' => 'area',
    'cast(sum(opc_area) AS DECIMAL (10, 3))' => 'area',
    'opc_board' => 'board',
    'u_truename' => 'scan',
    'opc_scan_datetime' => 'scan_datetime',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'c_name' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type',
    0 => 'type'
);
$config['order/scan_model/_select_order_product_board'] = array(
    'opb_id' => 'v',
    'cast(sum(opb_area) AS DECIMAL (10, 3))' => 'area',
    'opb_board' => 'board',
    'u_truename' => 'scan',
    'opb_scan_datetime' => 'scan_datetime',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'op_product' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type',
    1 => 'type'
);

$config['order/scan_model/select_scan_list'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_product' => 'product',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_remark' => 'remark'
);
