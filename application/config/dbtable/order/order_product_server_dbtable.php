<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_server_model/insert_batch'] = array(
	'opid' => 'ops_order_product_id',
	'sid' => 'ops_server_id',
	'name' => 'ops_server',
	'unit' => 'ops_unit',
	'unit_price' => 'ops_unit_price',
	'sum' => 'ops_sum',
	'amount' => 'ops_amount',
	'remark' => 'ops_remark',
);
$config['order/order_product_server_model/update_batch'] = array(
	'opsid' => 'ops_id',
	'opid' => 'ops_order_product_id',
	'sid' => 'ops_server_id',
	'name' => 'ops_server',
	'unit' => 'ops_unit',
	'amount' => 'ops_amount',
	'unit_price' => 'ops_unit_price',
	'sum' => 'ops_sum',
);
$config['order/order_product_server_model/update_batch_order_product_server'] = array(
	'opsid' => 'ops_id',
	'opid' => 'ops_order_product_id',
	'sid' => 'ops_server_id',
	'name' => 'ops_server',
	'unit' => 'ops_unit',
	'amount' => 'ops_amount',
	'unit_price' => 'ops_unit_price',
	'sum' => 'ops_sum',
);