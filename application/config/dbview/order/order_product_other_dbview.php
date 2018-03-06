<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_other_model/select_order_product_other_by_opid'] = array(
	'opo_other_id' => 'oid',
	'p_name' => 'type',
	'opo_other' => 'name',
	'opo_spec' => 'spec',
	'opo_unit' => 'unit',
	'opo_amount' => 'amount',
	'opo_unit_price' => 'unit_price',
	'opo_sum' => 'sum',
	'opo_remark' => 'remark',
);
$config['order/order_product_other_model/select_check_by_opid'] = array(
	'opo_id' => 'opoid',
	'op_id' => 'opid',
	'p_name' => 'type',
	'opo_other' => 'other',
	'opo_spec' => 'spec',
	'opo_unit' => 'unit',
	'if(opo_unit_price = 0, o_unit_price, opo_unit_price)' => 'unit_price',
	'opo_amount' => 'amount',
	'opo_sum' => 'sum',
	'opo_remark' => 'remark',
	'op_num' => 'order_product_num',
	'op_remark' => 'remarks',
);