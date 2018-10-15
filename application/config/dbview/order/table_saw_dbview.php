<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/table_saw_model/select'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board',
    'u_truename' => 'saw',
    'ifnull(opb_saw_datetime, opb_print_datetime)' => 'sort_datetime',
    'opb_saw_datetime' => 'saw_datetime',
    'sum(opb_amount)' => 'amount',
    'sum(opb_area)' => 'area',
    'wp_label' => 'status',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'p_name' => 'product',
    'p_code' => 'code',
    'o_order_type' => 'order_type'
);
