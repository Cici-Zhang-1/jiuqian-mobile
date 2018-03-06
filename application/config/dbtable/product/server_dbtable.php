<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/server_model/insert_server'] = array(
	'type' => 's_type_id',
	'name' => 's_name',
	'unit' => 's_unit',
	'unit_price' => 's_unit_price',
	'creator' => 's_creator',
	'create_datetime' => 's_create_datetime',
);
$config['product/server_model/update_server'] = array(
	'type' => 's_type_id',
	'name' => 's_name',
	'unit' => 's_unit',
	'unit_price' => 's_unit_price',
);
$config['product/server_model/update_batch'] = array(
	'sid' => 's_id',
	'unit_price' => 's_unit_price',
);