<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['position/position_order_product_model/insert'] = array(
	'selected' => 'pop_position_id',
	'opid' => 'pop_order_product_id',
	'status' => 'pop_status',
	'count' => 'pop_count',
	'creator' => 'pop_creator',
	'create_datetime' => 'pop_create_datetime',
);
$config['position/position_order_product_model/insert_batch'] = array(
	'selected' => 'pop_position_id',
	'opid' => 'pop_order_product_id',
	'status' => 'pop_status',
	'count' => 'pop_count',
	'creator' => 'pop_creator',
	'create_datetime' => 'pop_create_datetime',
);
$config['position/position_order_product_model/update'] = array(
	'status' => 'pop_status',
	'count' => 'pop_count',
	'destroy' => 'pop_destroy',
	'destroy_datetime' => 'pop_destroy_datetime',
);
$config['position/position_order_product_model/update_after_out'] = array(
	'status' => 'pop_status',
	'destroy' => 'pop_destroy',
	'destroy_datetime' => 'pop_destroy_datetime',
);
$config['position/position_order_product_model/delete'] = array(
	'status' => 'pop_status',
	'destroy' => 'pop_destroy',
	'destroy_datetime' => 'pop_destroy_datetime',
);