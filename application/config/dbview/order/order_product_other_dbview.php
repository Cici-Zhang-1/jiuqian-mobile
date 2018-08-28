<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_other_model/select'] = array(
	'opo_id' => 'v',
    'opo_goods_speci_id' => 'goods_speci_id',
	'opo_other' => array('other', 'name'),
	'opo_speci' => 'speci',
	'opo_unit' => 'unit',
	'opo_amount' => 'amount',
	'opo_unit_price' => 'unit_price',
	'opo_sum' => 'sum',
	'opo_remark' => 'remark',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'op_product' => 'product',
    'op_remark' => 'order_product_remark',
    'op_pack' => 'pack',
    'wop_label' => 'status_label',
    'p_code' => 'code'
);

$config['order/order_product_board_model/select_for_sure'] = array(
    'opo_id' => 'v'
);

$config['order/order_product_other_model/select_by_order_product_id'] = array(
    'opo_id' => 'v',
    'opo_goods_speci_id' => 'goods_speci_id',
    'opo_other' => 'other',
    'opo_speci' => 'speci',
    'opo_unit' => 'unit',
    'opo_amount' => 'amount',
    'opo_unit_price' => 'unit_price',
    'opo_sum' => 'sum',
    'opo_remark' => 'remark'
);