<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_server_model/select'] = array(
	'ops_id' => 'v',
    'ops_goods_speci_id' => 'goods_speci_id',
	'ops_server' => array('server', 'name'),
	'ops_speci' => 'speci',
	'ops_unit' => 'unit',
	'ops_amount' => 'amount',
	'ops_unit_price' => 'unit_price',
	'ops_sum' => 'sum',
	'ops_remark' => 'remark',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'op_product' => 'product',
    'op_remark' => 'order_product_remark',
    'wop_label' => 'status_label',
    'p_code' => 'code'
);

$config['order/order_product_server_model/select_by_order_product_id'] = array(
    'ops_id' => 'v',
    'ops_goods_speci_id' => 'goods_speci_id',
    'ops_server' => 'server',
    'ops_speci' => 'speci',
    'ops_unit' => 'unit',
    'ops_amount' => 'amount',
    'ops_unit_price' => 'unit_price',
    'ops_sum' => 'sum',
    'ops_remark' => 'remark'
);
