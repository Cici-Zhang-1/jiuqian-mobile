<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_other_model/insert_batch'] = array(
	'opid' => 'opo_order_product_id',
	'oid' => 'opo_other_id',
	'name' => 'opo_other',
	'spec' => 'opo_spec',
	'unit' => 'opo_unit',
	'unit_price' => 'opo_unit_price',
	'sum' => 'opo_sum',
	'amount' => 'opo_amount',
	'remark' => 'opo_remark',
);
$config['order/order_product_other_model/update_batch'] = array(
	'opoid' => 'opo_id',
	'opid' => 'opo_order_product_id',
	'oid' => 'opo_other_id',
	'name' => 'opo_other',
	'spec' => 'opo_spec',
	'unit' => 'opo_unit',
	'amount' => 'opo_amount',
	'unit_price' => 'opo_unit_price',
	'sum' => 'opo_sum',
);
$config['order/order_product_other_model/update_batch_order_product_other'] = array(
	'opoid' => 'opo_id',
	'opid' => 'opo_order_product_id',
	'oid' => 'opo_other_id',
	'name' => 'opo_other',
	'spec' => 'opo_spec',
	'unit' => 'opo_unit',
	'amount' => 'opo_amount',
	'unit_price' => 'opo_unit_price',
	'sum' => 'opo_sum',
);