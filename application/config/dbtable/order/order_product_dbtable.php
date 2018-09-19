<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_model/insert_order_product'] = array(
	'oid' => 'op_order_id',
	'product' => 'op_product',
	'remark' => 'op_remark',
	'parent' => 'op_parent',
);
$config['order/order_product_model/update'] = array(
	'product' => 'op_product',
	'remark' => 'op_remark',
	'bd' => 'op_bd',
	'pack' => 'op_pack',
	'pack_detail' => 'op_pack_detail',
	'packer' => 'op_packer',
	'pack_datetime' => 'op_pack_datetime',
    'warehouse_v' => 'op_warehouse_num',
    'status' => 'op_status',
    'creator' => 'op_creator',
    'create_datetime' => 'op_create_datetime',
    'dismantle' => 'op_dismantle',
    'dismantle_datetime' => 'op_dismantle_datetime',
    'pre_produce' => 'op_pre_produce',
    'pre_produce_datetime' => 'op_pre_produce_datetime',
    'producing' => 'op_producing',
    'producing_datetime' => 'op_producing_datetime',
    'inned' => 'op_inned',
    'inned_datetime' => 'op_inned_datetime',
    'delivered' => 'op_delivered',
    'design_atlas' => 'op_design_atlas'
);
$config['order/order_product_model/update_batch'] = array(
    'v' => 'op_id',
    'sum' => 'op_sum',
    'sum_diff' => 'op_sum_diff',
    'virtual_sum' => 'op_virtual_sum',
    'pack_detail' => 'op_pack_detail',
    'delivered' => 'op_delivered',
    'warehouse_v' => 'op_warehouse_num'
);
$config['order/order_product_model/update_workflow'] = array(
	'dismantle' => 'op_dismantle',
	'dismantle_datetime' => 'op_dismantle_datetime',
	'print_datetime' => 'op_print_datetime',
	'status' => 'op_status',
	'scan_status' => 'op_scan_status',
	'scan_start' => 'op_scan_start',
	'scan_end' => 'op_scan_end',
);
$config['order/order_product_model/update_status'] = array(
	'id' => 'op_id',
	'status' => 'op_status',
	'dismantle' => 'op_dismantle',
);
$config['order/order_product_model/insert_batch_order_product'] = array(
	'order_id' => 'op_order_id',
	'pid' => 'op_product_id',
	'num' => 'op_num',
	'dismantle' => 'op_dismantle',
);