<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_model/insert'] = array(
	'dealer_id' => 'o_dealer_id',
	'dealer' => 'o_dealer',
	'checker' => 'o_checker',
	'checker_phone' => 'o_checker_phone',
	'payterms' => 'o_payterms',
	'payer' => 'o_payer',
	'payer_phone' => 'o_payer_phone',
	'logistics' => 'o_logistics',
	'out_method' => 'o_out_method',
	'delivery_area' => 'o_delivery_area',
	'delivery_address' => 'o_delivery_address',
	'delivery_linker' => 'o_delivery_linker',
	'delivery_phone' => 'o_delivery_phone',
	'owner' => 'o_owner',
	'remark' => 'o_remark',
	'dealer_remark' => 'o_dealer_remark',
	'request_outdate' => 'o_request_outdate',
	'task_level' => 'o_task_level',
    'down_payment' => 'o_down_payment'
);

$config['order/order_model/_insert_datetime'] = array(
    'order_id' => 'od_order_id',
    'creator' => 'od_creator',
    'create_datetime' => 'od_create_datetime'
);
$config['order/order_model/update'] = array(
    'dealer_id' => 'o_dealer_id',
	'shop_id' => 'o_shop_id',
	'dealer' => 'o_dealer',
	'owner' => 'o_owner',
	'checker' => 'o_checker',
	'checker_phone' => 'o_checker_phone',
	'payterms' => 'o_payterms',
	'payer' => 'o_payer',
	'payer_phone' => 'o_payer_phone',
	'logistics' => 'o_logistics',
	'out_method' => 'o_out_method',
	'delivery_area' => 'o_delivery_area',
	'delivery_address' => 'o_delivery_address',
	'delivery_linker' => 'o_delivery_linker',
	'delivery_phone' => 'o_delivery_phone',
	'sum' => 'o_sum',
	'sum_detail' => 'o_sum_detail',
	'sum_diff' => 'o_sum_diff',
	'virtual_sum' => 'o_virtual_sum',
	'request_outdate' => 'o_request_outdate',
	'stock_outted_id' => 'o_stock_outted_id',
	'task_level' => 'o_task_level',
	'cargo_no' => 'o_cargo_no',
    'warehouse_v' => 'o_warehouse_num',
    'remark' => 'o_remark',
    'dealer_remark' => 'o_dealer_remark',
    'pay_status' => 'o_pay_status',
    'payed' => 'o_payed',
    'virtual_payed' => 'o_virtual_payed',
    'status' => 'o_status',
    'down_payment' => 'o_down_payment',
    'pack' => 'o_pack',
    'pack_detail' => 'o_pack_detail',
    'delivered' => 'o_delivered',
    'collection' => 'o_collection',
    'end_date' => 'o_end_date'
);
$config['order/order_model/update_workflow'] = array(
	'status' => 'o_status',
	'sum' => 'o_sum',
	'sum_detail' => 'o_sum_detail',
	'virtual_sum' => 'o_virtual_sum',
	'pack' => 'o_pack',
	'pack_detail' => 'o_pack_detail',
    'pay_status' => 'o_pay_status',
    'payed' => 'o_payed',
    'virtual_payed' => 'o_virtual_payed',
    'end_date' => 'o_end_date'
);
$config['order/order_model/update_batch'] = array(
	'v' => 'o_id',
	'pack' => 'o_pack',
	'pack_detail' => 'o_pack_detail',
	'cargo_no' => 'o_cargo_no',
    'warehouse_v' => 'o_warehouse_num',
    'delivered' => 'o_delivered',
    'collection' => 'o_collection',
    'end_date' => 'o_end_date'
);
$config['order/order_model/update_status'] = array(
	'id' => 'o_id',
	'status' => 'o_status',
);

$config['order/order_model/update_datetime'] = array(
    'creator' => 'od_creator',
    'create_datetime' => 'od_create_datetime',
    'dismantle' => 'od_dismantle',
    'dismantle_datetime' => 'od_dismantle_datetime',
    'valuate' => 'od_valuate',
    'valuate_datetime' => 'od_valuate_datetime',
    'check' => 'od_check',
    'check_datetime' => 'od_check_datetime',
    'sure' => 'od_sure',
    'sure_datetime' => 'od_sure_datetime',
    'producing' => 'od_producing',
    'producing_datetime' => 'od_producing_datetime',
    'inned' => 'od_inned',
    'inned_datetime' => 'od_inned_datetime',
    'delivery' => 'od_delivery',
    'delivery_datetime' => 'od_delivery_datetime'
);