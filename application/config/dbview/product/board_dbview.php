<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/board_model/select'] = array(
	'b_id' => 'bid',
	'b_name' => 'name',
	'b_length' => 'length',
	'b_width' => 'width',
	'concat(bt_id,"-",bt_name)' => 'thick_id',
	'bt_name' => 'thick',
	'concat(A.bc_id, "-", A.bc_name)' => 'color_id',
	'A.bc_name' => 'color',
	'concat(bn_id, "-", bn_name)' => 'nature_id',
	'bn_name' => 'nature',
	'concat(B.bc_id, "-", B.bc_name)' => 'class_id',
	'B.bc_name' => 'class',
	'concat(s_id, "-", s_name)' => 'supplier_id',
	's_name' => 'supplier',
	'b_remark' => 'remark',
	'b_unit_price' => 'unit_price',
	'b_amount' => 'amount',
);
$config['product/board_model/select_stock'] = array(
	'b_id' => 'bid',
	'b_name' => 'name',
	'bt_name' => 'thick',
	'b_remark' => 'remark',
	'b_amount' => 'amount',
);