<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/other_model/insert_other'] = array(
	'type' => 'o_type_id',
	'name' => 'o_name',
	'spec' => 'o_spec',
	'unit' => 'o_unit',
	'unit_price' => 'o_unit_price',
	'supplier' => 'o_supplier_id',
	'creator' => 'o_creator',
	'create_datetime' => 'o_create_datetime',
);
$config['product/other_model/update_other'] = array(
	'type' => 'o_type_id',
	'name' => 'o_name',
	'spec' => 'o_spec',
	'unit' => 'o_unit',
	'supplier' => 'o_supplier_id',
);
$config['product/other_model/update_batch'] = array(
	'oid' => 'o_id',
	'unit_price' => 'o_unit_price',
);