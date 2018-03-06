<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_board_model/insert'] = array(
	'opid' => 'opb_order_product_id',
	'board' => 'opb_board',
	'amount' => 'opb_amount',
	'area' => 'opb_area',
);
$config['order/order_product_board_model/update'] = array(
	'amount' => 'opb_amount',
	'area' => 'opb_area',
	'area_diff' => 'opb_area_diff',
	'unit_price' => 'opb_unit_price',
	'sum' => 'opb_sum',
	'sum_diff' => 'opb_sum_diff',
	'invisibility' => 'opb_invisibility',
	'invisibility_unit_price' => 'opb_invisibility_unit_price',
	'open_hole_unit_price' => 'opb_open_hole_unit_price',
	'open_hole' => 'opb_open_hole',
);
$config['order/order_product_board_model/update_batch'] = array(
	'opbid' => 'opb_id',
	'amount' => 'opb_amount',
	'area' => 'opb_area',
	'area_diff' => 'opb_area_diff',
	'unit_price' => 'opb_unit_price',
	'invisibility' => 'opb_invisibility',
	'open_hole' => 'opb_open_hole',
	'invisibility_unit_price' => 'opb_invisibility_unit_price',
	'open_hole_unit_price' => 'opb_open_hole_unit_price',
	'sum' => 'opb_sum',
	'sum_diff' => 'opb_sum_diff',
);
$config['order/order_product_board_model/update_status'] = array(
	'id' => 'opb_id',
	'status' => 'opb_status',
);