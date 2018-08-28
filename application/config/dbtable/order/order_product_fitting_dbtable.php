<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_fitting_model/insert_batch'] = array(
	'order_product_id' => 'opf_order_product_id',
	'goods_speci_id' => 'opf_goods_speci_id',
	'fitting' => 'opf_fitting',
	'speci' => 'opf_speci',
	'purchase' => 'opf_purchase',
	'purchase_unit' => 'opf_purchase_unit',
	'unit' => 'opf_unit',
	'unit_price' => 'opf_unit_price',
	'amount' => 'opf_amount',
	'remark' => 'opf_remark'
);
$config['order/order_product_fitting_model/update_batch'] = array(
	'v' => 'opf_id',
    'order_product_id' => 'opf_order_product_id',
    'goods_speci_id' => 'opf_goods_speci_id',
    'fitting' => 'opf_fitting',
    'speci' => 'opf_speci',
    'purchase' => 'opf_purchase',
    'purchase_unit' => 'opf_purchase_unit',
    'unit' => 'opf_unit',
    'unit_price' => 'opf_unit_price',
    'amount' => 'opf_amount',
    'remark' => 'opf_remark',
	'sum' => 'opf_sum',
    'status' => 'opf_status',
    'procedure' => 'opf_procedure',
    'production_line' => 'opf_production_line'
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