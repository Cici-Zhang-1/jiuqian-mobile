<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_server_model/insert_batch'] = array(
	'order_product_id' => 'ops_order_product_id',
	'goods_speci_id' => 'ops_goods_speci_id',
	'server' => 'ops_server',
    'speci' => 'ops_speci',
    'purchase' => 'ops_purchase',
    'purchase_unit' => 'ops_purchase_unit',
	'unit' => 'ops_unit',
	'unit_price' => 'ops_unit_price',
	'sum' => 'ops_sum',
	'amount' => 'ops_amount',
	'remark' => 'ops_remark'
);
$config['order/order_product_server_model/update_batch'] = array(
	'v' => 'ops_id',
    'goods_speci_id' => 'ops_goods_speci_id',
    'server' => 'ops_server',
    'speci' => 'ops_speci',
    'purchase' => 'ops_purchase',
    'purchase_unit' => 'ops_purchase_unit',
	'name' => 'ops_server',
	'unit' => 'ops_unit',
	'amount' => 'ops_amount',
	'unit_price' => 'ops_unit_price',
	'sum' => 'ops_sum',
    'remark' => 'ops_remark'
);