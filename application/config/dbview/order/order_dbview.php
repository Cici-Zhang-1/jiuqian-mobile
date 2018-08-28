<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_model/is_deliveriable'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
	'o_pack' => 'pack',
	'o_pack_detail' => 'pack_detail',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_remark' => 'remark',
	'o_delivery_area' => 'delivery_area',
	'o_delivery_address' => 'delivery_address',
	'o_delivery_linker' => 'delivery_linker',
	'o_delivery_phone' => 'delivery_phone',
);

$config['order/order_model/is_exist_order_num'] = array(
    'o_id' => 'v'
);

$config['order/order_model/is_dismantlable'] = array(
	'o_id' => 'order_id',
	'o_num' => 'num',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_status' => 'status',
	'o_sum' => 'sum'
);

$config['order/order_model/is_re_dismantlable'] = array(
    'o_id' => 'order_id',
    'o_num' => 'num'
);

$config['order/order_model/is_redeliveriable'] = array(
	'o_id' => 'v',
	'o_sum' => 'sum',
	'o_dealer_id' => 'did',
);

$config['order/order_model/select_current_workflow'] = array(
	'wo_id' => 'v',
	'wo_name' => 'name',
	'wo_label' => 'label',
	'wo_file' => 'file',
);

$config['order/order_model/select_delivered'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
	'o_sum' => 'sum',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_delivery_area' => 'delivery_area',
	'o_delivery_address' => 'delivery_address',
	'o_delivery_linker' => 'delivery_linker',
	'o_delivery_phone' => 'delivery_phone',
	'o_remark' => 'remark',
	'o_pack' => 'pack',
	'o_logistics' => 'logistics',
	'o_end_datetime' => 'end_datetime',
	'so_truck' => 'truck',
	'so_train' => 'train',
	'u_truename' => 'creator',
);

$config['order/order_model/select'] = array(
	'o_id' => 'v',
    'o_task_level' => 'task_level',
    'tl_icon' => 'icon',
	'o_num' => 'num',
	'o_dealer_id' => 'dealer_id',
	'o_shop_id' => 'shop_id',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
    'o_sum' => 'sum',
	'o_sum_detail' => 'sum_detail',
	'o_payed' => 'payed',
	'o_pay_status' => 'pay_status',
	'ps_label' => 'pay_status_label',
	'A.u_truename' => 'creator',
	'o_request_outdate' => 'request_outdate',
	'o_status' => 'status',
	'wo_label' => 'status_label',
	'o_checker' => 'checker',
	'o_checker_phone' => 'checker_phone',
	'o_payer' => 'payer',
	'o_payer_phone' => 'payer_phone',
	'o_logistics' => 'logistics',
	'o_out_method' => 'out_method',
	'o_delivery_area' => 'delivery_area',
	'o_delivery_address' => 'delivery_address',
	'o_delivery_linker' => 'delivery_linker',
	'o_delivery_phone' => 'delivery_phone',
    'o_remark' => 'remark',
    'o_dealer_remark' => 'dealer_remark',
);
$config['order/order_model/select_valuate'] = array(
    'o_id' => 'v',
    'tl_icon' => 'icon',
    'o_num' => 'num',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_sum' => 'sum',
    'o_sum_detail' => 'sum_detail',
    'A.u_truename' => 'creator',
    'o_request_outdate' => 'request_outdate',
    'wo_label' => 'status_label',
    'o_payer' => 'payer',
    'o_payer_phone' => 'payer_phone',
    'o_remark' => 'remark',
    'od_dismantle_datetime' => 'dismantle_datetime'
);
$config['order/order_model/select_check'] = array( //财务核价
    'o_id' => 'v',
    'tl_icon' => 'icon',
    'o_num' => 'num',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_sum' => 'sum',
    'o_remark' => 'remark',
    'od_valuate_datetime' => 'valuate_datetime',
    'od_check_remark' => 'check_remark',
    'V.u_truename' => 'valuate'
);

$config['order/order_model/select_wait_sure'] = array(
    'o_id' => 'v',
    'tl_icon' => 'icon',
    'o_num' => 'num',
    'o_dealer_id' => 'dealer_id',
    'o_dealer' => 'dealer',
    'o_shop_id' => 'shop_id',
    'o_owner' => 'owner',
    'o_sum' => 'sum',
    'ps_label' => 'pay_status_label',
    'o_request_outdate' => 'request_outdate',
    'o_payer' => 'payer',
    'o_payer_phone' => 'payer_phone',
    'o_remark' => 'remark',
    'od_check_datetime' => 'check_datetime',
    'd_balance' => 'balance',
    'o_payterms' => 'payterms',
    'o_down_payment' => 'down_payment',
);

$config['order/order_model/select_produce'] = array( //财务核价
    'o_id' => 'v',
    'tl_icon' => 'icon',
    'o_num' => 'num',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_remark' => 'remark',
    'o_request_outdate' => 'request_outdate',
    'od_sure_datetime' => 'sure_datetime',
    'S.u_truename' => 'sure'
);

$config['order/order_model/select_detail'] = array(
    'o_id' => 'v',
    'tl_icon' => 'icon',
    'o_order_type' => 'order_type',
    'o_num' => 'num',
    'o_dealer_id' => 'dealer_id',
    'o_dealer' => 'dealer',
    'o_shop_id' => 'shop_id',
    'o_owner' => 'owner',
    'o_sum' => 'sum',
    'o_payed' => 'payed',
    'ps_label' => 'pay_status_label',
    'u_truename' => 'creator',
    'o_request_outdate' => 'request_outdate',
    'wo_label' => 'status_label',
    'o_checker' => 'checker',
    'o_checker_phone' => 'checker_phone',
    'o_payer' => 'payer',
    'o_payer_phone' => 'payer_phone',
    'o_logistics' => 'logistics',
    'o_out_method' => 'out_method',
    'o_delivery_area' => 'delivery_area',
    'o_delivery_address' => 'delivery_address',
    'o_delivery_linker' => 'delivery_linker',
    'o_delivery_phone' => 'delivery_phone',
    'o_remark' => 'remark',
    'o_dealer_remark' => 'dealer_remark',
    'C.wom_create_datetime' => 'create_datetime',
    'A.wom_create_datetime' => 'produce_datetime',
    'ifnull(D.wom_create_datetime, ifnull(M.wom_create_datetime, ifnull(B.wom_create_datetime, "")))' => 'end_datetime'
);

$config['order/order_model/select_details'] = array(
    'o_id' => 'v',
    'o_num' => 'num',
    'o_remark' => 'remark'
);

$config['order/order_model/select_order_detail'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
	'o_dealer_id' => 'did',
	'o_dealer' => 'dealer',
	'o_checker' => 'checker',
	'o_checker_phone' => 'checker_phone',
	'o_payterms' => 'payterms',
	'o_payer' => 'payer',
	'o_payer_phone' => 'payer_phone',
	'o_logistics' => 'logistics',
	'o_out_method' => 'out_method',
	'o_delivery_area' => 'delivery_area',
	'o_delivery_address' => 'delivery_address',
	'o_delivery_linker' => 'delivery_linker',
	'o_delivery_phone' => 'delivery_phone',
	'o_owner' => 'owner',
	'o_remark' => 'remark',
	'o_dealer_remark' => 'dealer_remark',
	'o_request_outdate' => 'request_outdate',
	'o_payed_datetime' => 'payed_datetime',
	'o_cargo_no' => 'cargo_no',
	'u_truename' => 'creator',
	'o_create_datetime' => 'create_datetime',
	'o_end_datetime' => 'end_datetime',
	'o_asure_datetime' => 'asure_datetime',
	'o_sum' => 'sum',
	'o_sum_detail' => 'sum_detail',
	'o_status' => 'status',
	'w_name' => 'workflow',
);
$config['order/order_model/select_wait_delivery'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
	'o_sum' => 'sum',
	'o_owner' => 'owner',
	'o_delivery_area' => 'delivery_area',
	'o_delivery_address' => 'delivery_address',
	'o_delivery_linker' => 'delivery_linker',
	'o_delivery_phone' => 'delivery_phone',
	'o_remark' => 'remark',
	'o_request_outdate' => 'request_outdate',
	'o_pack' => 'pack',
	'o_pack_detail' => 'pack_detail',
	'o_logistics' => 'logistics',
	'if(o_payed_datetime > 0, "已付", o_payterms)' => 'payed',
	'd_id' => 'did',
	'd_balance - d_debt2' => 'balance',
	'tl_icon' => 'icon',
);
$config['order/order_model/select_wait_delivery_by_ids'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
	'o_sum' => 'sum',
	'o_dealer_id' => 'did',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_delivery_area' => 'delivery_area',
	'o_delivery_address' => 'delivery_address',
	'o_delivery_linker' => 'delivery_linker',
	'o_delivery_phone' => 'delivery_phone',
	'o_remark' => 'remark',
	'o_pack' => 'pack',
	'o_pack_detail' => 'pack_detail',
	'o_logistics' => 'logistics',
	'if(o_payed_datetime > 0, "已付", o_payterms)' => 'payed',
	'd_balance' => 'balance',
);
$config['order/order_model/select_by_soid'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
	'o_sum' => 'sum',
	'o_dealer_id' => 'did',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_delivery_area' => 'delivery_area',
	'o_delivery_address' => 'delivery_address',
	'o_delivery_linker' => 'delivery_linker',
	'o_delivery_phone' => 'delivery_phone',
	'o_remark' => 'remark',
	'o_pack' => 'pack',
	'o_pack_detail' => 'pack_detail',
	'o_logistics' => 'logistics',
	'if(o_payed_datetime > 0, "已付", o_payterms)' => 'payed',
	'd_balance' => 'balance',
	'o_stock_outted_id' => 'soid',
	'o_cargo_no' => 'cargo_no',
);
$config['order/order_model/select_order_stock_logistics'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
	'o_dealer_id' => 'did',
	'o_dealer' => 'dealer',
	'o_payterms' => 'payterms',
	'o_payer' => 'payer',
	'o_payer_phone' => 'payer_phone',
	'o_logistics' => 'logistics',
	'o_delivery_area' => 'delivery_area',
	'o_delivery_address' => 'delivery_address',
	'o_delivery_linker' => 'delivery_linker',
	'o_delivery_phone' => 'delivery_phone',
	'o_owner' => 'owner',
	'o_remark' => 'remark',
	'o_request_outdate' => 'request_outdate',
	'o_asure_datetime' => 'asure_datetime',
	'o_sum' => 'sum',
	'd_debt1' => 'debt1',
	'd_debt2' => 'debt2',
	'd_balance' => 'balance',
);
$config['order/order_model/select_order_dealer_by_id'] = array(
	'o_id' => 'v',
	'o_sum' => 'sum',
	'o_dealer_id' => 'did',
);
$config['order/order_model/select_order_num'] = array(
	'o_num' => 'order_num',
	'o_sum' => 'sum',
	'ifnull(o_payed_datetime, "")' => 'payed_datetime',
);
$config['order/order_model/select_order_num_by_cargo_no'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
	'o_sum' => 'sum',
	'o_dealer_id' => 'did',
	'o_dealer' => 'dealer',
	'ifnull(o_payed_datetime, "")' => 'payed_datetime',
);
$config['order/order_model/select_status'] = array(
	'o_id' => 'id',
	'o_status' => 'status',
);
$config['order/order_detail/_read_detail'] = array(
	'op_id' => 'opid',
	'p_id' => 'pid',
	'p_name' => 'name',
	'p_code' => 'code',
	'op_parent' => 'parent',
	'op_num' => 'order_product_num',
	'op_product' => 'product',
	'op_remark' => 'remark',
	'w_name' => 'status',
	'op_pack' => 'pack',
);
$config['order/order_quote/_read_detail'] = array(
	'op_id' => 'opid',
	'p_id' => 'pid',
	'p_name' => 'name',
	'p_code' => 'code',
	'op_parent' => 'parent',
	'op_num' => 'order_product_num',
	'op_product' => 'product',
	'op_remark' => 'remark',
	'w_name' => 'status',
	'op_pack' => 'pack',
);

$config['order/order_model/is_editable'] = array(
    'o_id' => 'v',
    'o_status' => 'status'
);

$config['order/order_model/is_status'] = array(
    'o_id' => 'v',
    'o_status' => 'status'
);

$config['order/order_model/are_status'] = array(
    'o_id' => 'v',
    'o_status' => 'status'
);

$config['order/order_model/are_applicable'] = array(
    'o_id' => 'v',
    'o_dealer_id' => 'dealer_id',
    'o_sum' => 'sum',
    'o_down_payment' => 'down_payment',
    'o_status' => 'status',
    'd_balance' => 'dealer_balance',
    'o_payterms' => 'payterms'
);