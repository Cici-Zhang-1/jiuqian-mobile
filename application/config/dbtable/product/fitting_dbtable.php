<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/fitting_model/insert_fitting'] = array(
	'type' => 'f_type_id',
	'name' => 'f_name',
	'unit' => 'f_unit',
	'unit_price' => 'f_unit_price',
	'speci' => 'f_speci',
	'together' => 'f_together',
	'supplier' => 'f_supplier_id',
	'creator' => 'f_creator',
	'create_datetime' => 'f_create_datetime',
);
$config['product/fitting_model/update_fitting'] = array(
	'type' => 'f_type_id',
	'name' => 'f_name',
	'unit' => 'f_unit',
	'unit_price' => 'f_unit_price',
	'speci' => 'f_speci',
	'together' => 'f_together',
	'supplier' => 'f_supplier_id',
	'amount' => 'f_amount',
);
$config['product/fitting_model/update_batch'] = array(
	'fid' => 'f_id',
	'type' => 'f_type_id',
	'name' => 'f_name',
	'unit' => 'f_unit',
	'unit_price' => 'f_unit_price',
	'speci' => 'f_speci',
	'together' => 'f_together',
	'supplier' => 'f_supplier_id',
	'amount' => 'f_amount',
);