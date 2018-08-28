<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_other_model/insert_batch'] = array(
	'order_product_id' => 'opo_order_product_id',
	'goods_speci_id' => 'opo_goods_speci_id',
	'other' => 'opo_other',
	'speci' => 'opo_speci',
    'purchase' => 'opo_purchase',
    'purchase_unit' => 'opo_purchase_unit',
	'unit' => 'opo_unit',
	'unit_price' => 'opo_unit_price',
	'sum' => 'opo_sum',
	'amount' => 'opo_amount',
	'remark' => 'opo_remark'
);
$config['order/order_product_other_model/update_batch'] = array(
	'v' => 'opo_id',
	'order_product_id' => 'opo_order_product_id',
    'goods_speci_id' => 'opo_goods_speci_id',
    'other' => 'opo_other',
	'speci' => 'opo_speci',
    'purchase' => 'opo_purchase',
    'purchase_unit' => 'opo_purchase_unit',
	'unit' => 'opo_unit',
	'unit_price' => 'opo_unit_price',
    'amount' => 'opo_amount',
	'sum' => 'opo_sum',
    'remark' => 'opo_remark',
    'status' => 'opo_status',
    'procedure' => 'opo_procedure',
    'production_line' => 'opo_production_line'
);