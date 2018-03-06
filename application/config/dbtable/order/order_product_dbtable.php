<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_model/insert_order_product'] = array(
	'oid' => 'op_order_id',
	'product' => 'op_product',
	'remark' => 'op_remark',
	'parent' => 'op_parent',
);
$config['order/order_product_model/update_batch'] = array(
	'opid' => 'op_id',
	'sum' => 'op_sum',
	'sum_diff' => 'op_sum_diff',
	'pack_detail' => 'op_pack_detail',
);
$config['order/order_product_model/update'] = array(
	'product' => 'op_product',
	'remark' => 'op_remark',
	'parent' => 'op_parent',
	'bd' => 'op_bd',
	'dismantler' => 'op_dismantler',
	'pack' => 'op_pack',
	'pack_detail' => 'op_pack_detail',
	'packer' => 'op_packer',
	'pack_datetime' => 'op_pack_datetime',
);
$config['order/order_product_model/update_workflow'] = array(
	'dismantler' => 'op_dismantler',
	'dismantled_datetime' => 'op_dismantled_datetime',
	'print_datetime' => 'op_print_datetime',
	'status' => 'op_status',
	'scan_status' => 'op_scan_status',
	'scan_start' => 'op_scan_start',
	'scan_end' => 'op_scan_end',
);
$config['order/order_product_model/update_status'] = array(
	'id' => 'op_id',
	'status' => 'op_status',
	'dismantler' => 'op_dismantler',
);
$config['order/order_product_model/insert_batch_order_product'] = array(
	'order_id' => 'op_order_id',
	'pid' => 'op_product_id',
	'num' => 'op_num',
	'dismantler' => 'op_dismantler',
);