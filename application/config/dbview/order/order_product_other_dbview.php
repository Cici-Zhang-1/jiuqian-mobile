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
    'p_code' => 'code',
    'wp_label' => 'procedure_status_label'
);


$config['order/order_product_other_model/select_for_post_sale'] = array(
    'opo_status' => 'status',
    'opo_procedure' => 'procedure',
    'opo_production_line' => 'production_line',
    'opo_print' => 'print',
    'opo_print_datetime' => 'print_datetime',
    'opo_pack' => 'pack',
    'opo_pack_datetime' => 'pack_datetime'
);
$config['order/order_product_other_model/select_for_sure'] = array(
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
    'opo_purchase' => 'purchase',
    'opo_sum' => 'sum',
    'opo_remark' => 'remark'
);

$config['order/order_product_other_model/select_sales'] = array(
    'opo_id' => 'v',
    'opo_sum' => 'sum',
    'o_dealer_id' => 'dealer_id',
    'o_dealer' => 'dealer',
    'p_id' => 'product_id',
    'p_name' => 'product'
);


$config['order/order_product_other_model/select_current_workflow'] = array(
    'wp_id' => 'v',
    'wp_name' => 'name',
    'wp_label' => 'label',
    'wp_file' => 'file'
);

$config['order/order_product_other_model/select_order_product_id'] = array(
    'opo_order_product_id' => 'order_product_id'
);

$config['order/order_product_other_model/select_packable_by_order_product_id'] = array(
    'opo_id' => 'v',
    'opo_status' => 'status',
    'opo_procedure' => 'procedure'
);