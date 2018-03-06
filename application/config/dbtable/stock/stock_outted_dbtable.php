<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['stock/stock_outted_model/insert'] = array(
	'amount' => 'so_amount',
	'truck' => 'so_truck',
	'train' => 'so_train',
	'end_datetime' => 'so_end_datetime',
	'logistics' => 'so_logistics',
	'creator' => 'so_creator',
	'create_datetime' => 'so_create_datetime',
);
$config['stock/stock_outted_model/update_stock_outted'] = array(
	'cargo_no' => 'so_cargo_no',
);