<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_server_model/select_order_product_server_by_opid'] = array(
	'ops_server_id' => 'sid',
	'p_name' => 'type',
	'ops_server' => 'name',
	'ops_unit' => 'unit',
	'ops_amount' => 'amount',
	'ops_unit_price' => 'unit_price',
	'ops_sum' => 'sum',
	'ops_remark' => 'remark',
);
$config['order/order_product_server_model/select_check_by_opid'] = array(
	'ops_id' => 'opsid',
	'op_id' => 'opid',
	'p_name' => 'type',
	'ops_server' => 'server',
	'ops_amount' => 'amount',
	'ops_unit' => 'unit',
	'if(ops_unit_price = 0, s_unit_price, ops_unit_price)' => 'unit_price',
	'ops_sum' => 'sum',
	'ops_remark' => 'remark',
	'op_num' => 'order_product_num',
	'op_remark' => 'remarks',
);