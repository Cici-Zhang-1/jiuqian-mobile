<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_fitting_model/insert_batch'] = array(
	'opid' => 'opf_order_product_id',
	'fid' => 'opf_fitting_id',
	'name' => 'opf_fitting',
	'unit' => 'opf_unit',
	'unit_price' => 'opf_unit_price',
	'sum' => 'opf_sum',
	'amount' => 'opf_amount',
	'remark' => 'opf_remark',
);
$config['order/order_product_fitting_model/update_batch'] = array(
	'opfid' => 'opf_id',
	'opid' => 'opf_order_product_id',
	'fid' => 'opf_fitting_id',
	'name' => 'opf_fitting',
	'unit' => 'opf_unit',
	'amount' => 'opf_amount',
	'unit_price' => 'opf_unit_price',
	'sum' => 'opf_sum',
);
$config['order/order_product_fitting_model/update_batch_order_product_fitting'] = array(
	'opfid' => 'opf_id',
	'opid' => 'opf_order_product_id',
	'fid' => 'opf_fitting_id',
	'name' => 'opf_fitting',
	'unit' => 'opf_unit',
	'amount' => 'opf_amount',
	'unit_price' => 'opf_unit_price',
	'sum' => 'opf_sum',
);