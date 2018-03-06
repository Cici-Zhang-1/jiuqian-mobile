<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_fitting_model/select_order_product_fitting_by_opid'] = array(
	'opf_id' => 'opfid',
	'opf_fitting_id' => 'fid',
	'p_name' => 'type',
	'opf_fitting' => 'name',
	'opf_unit' => 'unit',
	'opf_amount' => 'amount',
	'opf_unit_price' => 'unit_price',
	'opf_sum' => 'sum',
	'opf_remark' => 'remark',
	'op_num' => 'order_product_num',
	'o_dealer' => 'dealer',
);
$config['order/order_product_fitting_model/select_check_by_opid'] = array(
	'opf_id' => 'opfid',
	'op_id' => 'opid',
	'p_name' => 'type',
	'opf_fitting' => 'fitting',
	'opf_amount' => 'amount',
	'opf_unit' => 'unit',
	'if(opf_unit_price = 0, f_unit_price, opf_unit_price)' => 'unit_price',
	'opf_sum' => 'sum',
	'opf_remark' => 'remark',
	'op_num' => 'order_product_num',
	'op_remark' => 'remarks',
);