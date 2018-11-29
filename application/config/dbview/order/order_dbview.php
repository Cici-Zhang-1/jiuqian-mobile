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
    'o_id' => 'v',
    'o_status' => 'status'
);

$config['order/order_model/is_dismantlable'] = array(
	'o_id' => 'order_id',
	'o_num' => 'num',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_status' => 'status',
    'o_sum' => 'sum',
    'o_actual_sum' => 'actual_sum',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
);

$config['order/order_model/is_re_dismantlable'] = array(
    'o_id' => 'order_id',
    'o_num' => 'num'
);

$config['order/order_model/is_redeliveriable'] = array(
	'o_id' => 'v',
	'o_dealer_id' => 'did',
    'o_sum' => 'sum',
    'o_actual_sum' => 'actual_sum',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum'
);

$config['order/order_model/select_current_workflow'] = array(
	'wo_id' => 'v',
	'wo_name' => 'name',
	'wo_label' => 'label',
	'wo_file' => 'file',
);

$config['order/order_model/is_order_post_salable'] = array(
    'o_id' => 'order_id',
    'o_num' => 'num',
    'o_dealer' => 'dealer',
    'o_dealer_id' => 'dealer_id',
    'o_owner' => 'owner',
    'o_status' => 'status',
    'o_theory_sum' => 'theory_sum',
    'o_sum' => 'sum',
    'o_actual_sum' => 'actual_sum',
    'o_actual_sum_detail' => 'actual_sum_detail',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_payed' => 'payed',
    'o_pay_status' => 'pay_status',
    'd_balance' => 'dealer_balance',
    'd_produce' => 'dealer_produce',
    'd_delivered' => 'dealer_delivered',
    'd_actual_balance' => 'dealer_actual_balance',
    'd_actual_produce' => 'dealer_actual_produce',
    'd_actual_delivered' => 'dealer_actual_delivered'
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
	'A.u_truename' => 'creator',
	'o_request_outdate' => 'request_outdate',
	'o_end_datetime' => 'end_datetime',
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
    'o_pay_status' => 'pay_status',
    'ps_label' => 'pay_status_label',
);
$config['order/order_model/select_valuate'] = array(
    'o_id' => 'v',
    'tl_icon' => 'icon',
    'o_num' => 'num',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'A.u_truename' => 'creator',
    'o_request_outdate' => 'request_outdate',
    'wo_label' => 'status_label',
    'o_payer' => 'payer',
    'o_payer_phone' => 'payer_phone',
    'o_remark' => 'remark',
    'od_dismantle_datetime' => 'dismantle_datetime',
    'o_theory_sum' => 'theory_sum',
    'o_sum' => 'sum',
    'o_actual_sum' => 'actual_sum',
    'o_actual_sum_detail' => 'actual_sum_detail',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum'
);
$config['order/order_model/select_check'] = array( //财务核价
    'o_id' => 'v',
    'tl_icon' => 'icon',
    'o_num' => 'num',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_remark' => 'remark',
    'od_valuate_datetime' => 'valuate_datetime',
    'od_check_remark' => 'check_remark',
    'V.u_truename' => 'valuate',
    'o_theory_sum' => 'theory_sum',
    'o_sum' => 'sum',
    'o_actual_sum' => 'actual_sum',
    'o_actual_sum_detail' => 'actual_sum_detail',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum'
);

$config['order/order_model/select_wait_sure'] = array(
    'o_id' => 'v',
    'tl_icon' => 'icon',
    'o_num' => 'num',
    'o_dealer_id' => 'dealer_id',
    'o_dealer' => 'dealer',
    'o_shop_id' => 'shop_id',
    'o_owner' => 'owner',
    'ps_label' => 'pay_status_label',
    'o_request_outdate' => 'request_outdate',
    'o_payer' => 'payer',
    'o_payer_phone' => 'payer_phone',
    'o_remark' => 'remark',
    'od_check_datetime' => 'check_datetime',
    'd_balance' => 'balance',
    'd_virtual_balance' => 'virtual_balance',
    'o_payterms' => 'payterms',
    'o_down_payment' => 'down_payment',
    'ifnull(a_id, "")' => 'application_id',
    'ifnull(as_icon, "")' => 'application_status_label',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum'
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
    'o_task_level' => 'task_level',
    'tl_icon' => 'icon',
    'o_order_type' => 'order_type',
    'o_num' => 'num',
    'o_dealer_id' => 'dealer_id',
    'o_dealer' => 'dealer',
    'o_shop_id' => 'shop_id',
    'o_owner' => 'owner',
    'o_payterms' => 'payterms',
    'o_down_payment' => 'down_payment',
    'o_request_outdate' => 'request_outdate',
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
    'o_status' => 'status',
    'o_warehouse_num' => 'warehouse_num',
    'C.u_truename' => 'creator',
    'od_create_datetime' => 'create_datetime',
    'od_sure_datetime' => 'produce_datetime',
    'od_delivery_datetime' => 'end_datetime',
    'wo_label' => 'status_label',
    'o_theory_sum' => 'theory_sum',
    'o_sum' => 'sum',
    'o_actual_sum' => 'actual_sum',
    'o_actual_sum_detail' => 'actual_sum_detail',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_payed' => 'payed',
    'o_pay_status' => 'pay_status',
    'ps_label' => 'pay_status_label',
    'd_balance' => 'dealer_balance',
    'd_produce' => 'dealer_produce',
    'd_delivered' => 'dealer_delivered',
    'd_actual_balance' => 'dealer_actual_balance',
    'd_actual_produce' => 'dealer_actual_produce',
    'd_actual_delivered' => 'dealer_actual_delivered'
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
	'o_status' => 'status',
	'w_name' => 'workflow',
    'o_theory_sum' => 'theory_sum',
    'o_sum' => 'sum',
    'o_actual_sum' => 'actual_sum',
    'o_actual_sum_detail' => 'actual_sum_detail',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum'
);
$config['order/order_model/select_wait_delivery'] = array(
	'o_id' => 'v',
	'o_num' => 'num',
	'o_collection' => 'collection',
	'o_payterms' => 'payterms',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_delivery_area' => 'delivery_area',
	'o_delivery_address' => 'delivery_address',
	'o_delivery_linker' => 'delivery_linker',
	'o_delivery_phone' => 'delivery_phone',
	'o_remark' => 'remark',
	'o_request_outdate' => 'request_outdate',
	'o_pack' => 'pack',
	'o_pack_detail' => 'pack_detail',
	'o_delivered' => 'delivered',
	'(o_pack - o_delivered)' => 'wait_delivery',
	'o_logistics' => 'logistics',
	'd_id' => 'dealer_id',
    'a_id' => 'application_id',
    'a_status' => 'application_status',
    'o_theory_sum' => 'theory_sum',
    'o_sum' => 'sum',
    'o_actual_sum' => 'actual_sum',
    'o_actual_sum_detail' => 'actual_sum_detail',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_payed' => 'payed',
    'o_pay_status' => 'pay_status',
    'ps_label' => 'pay_status_label',
    'd_balance' => 'dealer_balance',
    'd_produce' => 'dealer_produce',
    'd_delivered' => 'dealer_delivered',
    'd_actual_balance' => 'dealer_actual_balance',
    'd_actual_produce' => 'dealer_actual_produce',
    'd_actual_delivered' => 'dealer_actual_delivered'
);
$config['order/order_model/select_overdue'] = array(
    'o_id' => 'v',
    'o_num' => 'num',
    'o_actual_sum' => 'actual_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_payed' => 'payed',
    'o_pay_status' => 'pay_status',
    'o_dealer' => 'dealer',
    'u_truename' => 'owner',
    'o_remark' => 'remark',
    'd_id' => 'dealer_id',
    'd_balance' => 'balance',
    'd_actual_balance' => 'actual_balance',
    'ps_label' => 'pay_status_label',
    'od_inned_datetime' => 'inned_datetime'
);
$config['order/order_model/select_wait_delivery_by_ids'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
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
    'd_actual_balance' => 'actual_balance'
);
$config['order/order_model/select_by_soid'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
	'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
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
	'd_actual_balance' => 'actual_balance',
	'o_stock_outted_id' => 'soid',
	'o_cargo_no' => 'cargo_no'
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
	'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
	'd_balance' => 'balance',
    'd_actual_balance' => 'actual_balance'
);
$config['order/order_model/select_order_dealer_by_id'] = array(
	'o_id' => 'v',
	'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
	'o_dealer_id' => 'did'
);
$config['order/order_model/select_order_num'] = array(
	'o_num' => array(
	    'v',
        'order_num'
    ),
    'concat(o_num, "-", o_sum, "-", ifnull(od_payed_datetime, ""))' => 'label'
);
$config['order/order_model/select_order_num_by_cargo_no'] = array(
	'o_id' => 'v',
	'o_num' => 'order_num',
	'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
	'o_dealer_id' => 'did',
	'o_dealer' => 'dealer',
	'ifnull(o_payed_datetime, "")' => 'payed_datetime'
);
$config['order/order_model/select_status'] = array(
	'o_id' => 'id',
	'o_status' => 'status'
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
	'op_pack' => 'pack'
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
    'o_num' => 'num',
    'o_dealer_id' => 'dealer_id',
    'o_quote_sum' => 'quote_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_payed' => 'payed',
    'o_down_payment' => 'down_payment',
    'o_status' => 'status',
    'd_balance' => 'dealer_balance',
    'd_actual_balance' => 'dealer_actual_balance',
    'o_payterms' => 'payterms'
);

$config['order/order_model/select_delivered'] = array(
    'o_id' => 'v',
    'o_delivered' => 'delivered'
);

$config['order/order_model/select_order_sorter'] = array(
    'o_id' => 'v',
    'o_num' => 'num',
    'o_quote_sum' => 'quote_sum',
    'o_actual_sum_detail' => 'actual_sum_detail',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_dealer_id' => 'dealer_id',
    'o_dealer' => 'dealer'
);

$config['order/order_model/select_after_wait_sure'] = array(
    'o_actual_sum' => 'actual_sum',
    'o_actual_sum_detail' => 'actual_sum_detail',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_status' => 'status'
);

$config['order/order_model/select_after_produce'] = array(
    'o_actual_sum' => 'actual_sum',
    'o_actual_sum_detail' => 'actual_sum_detail',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_status' => 'status',
    'od_sure_datetime' => 'sure_datetime'
);

$config['order/order_model/select_sample_after_produce'] = array(
    'o_actual_sum' => 'actual_sum',
    'o_actual_sum_detail' => 'actual_sum_detail',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_status' => 'status',
    'od_sure_datetime' => 'sure_datetime'
);

$config['order/order_model/select_everyday_sured'] = array(
    'o_id' => array(
        'v',
        'order_id'
    ),
    'o_num' => 'num',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_actual_sum' => 'actual_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'A.thick' => 'thick',
    'u_truename' => 'sure',
    'od_sure_datetime' => 'sure_datetime'
);

$config['order/order_model/are_removable'] = array(
    'o_id' => array(
        'v',
        'order_id'
    ),
    'o_num' => 'order_num',
    'o_payterms' => 'payterms',
    'o_down_payment' => 'down_payment',
    'o_actual_sum' => 'actual_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_payed' => 'payed',
    'o_pay_status' => 'pay_status',
    'd_id' => 'dealer_id',
    'd_balance' => 'dealer_balance',
    'd_produce' => 'dealer_produce',
    'd_delivered' => 'dealer_delivered',
    'd_actual_balance' => 'dealer_actual_balance',
    'd_actual_produce' => 'dealer_actual_produce',
    'd_actual_delivered' => 'dealer_actual_delivered',
    'o_status' => 'status'
);

$config['order/order_model/are_directable'] = array(
    'o_id' => array(
        'v',
        'order_id'
    ),
    'o_num' => 'order_num',
    'o_payterms' => 'payterms',
    'o_down_payment' => 'down_payment',
    'o_actual_sum' => 'actual_sum',
    'o_quote_actual_sum' => 'quote_actual_sum',
    'o_payed' => 'payed',
    'o_pay_status' => 'pay_status',
    'd_id' => 'dealer_id',
    'd_balance' => 'dealer_balance',
    'd_produce' => 'dealer_produce',
    'd_delivered' => 'dealer_delivered',
    'd_actual_balance' => 'dealer_actual_balance',
    'd_actual_produce' => 'dealer_actual_produce',
    'd_actual_delivered' => 'dealer_actual_delivered',
    'o_status' => 'status'
);

$config['order/order_model/select_for_debt'] = array(
    'o_id' => 'oid',
    'o_num' => 'order_num',
    'o_remark' => 'remark',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'od_delivery_datetime' => 'delivery_datetime',
    'od_sure_datetime' => 'sure_datetime',
    'od_payed_datetime' => 'payed_datetime',
    'o_actual_sum' => 'actual_sum',
    'o_quote_actual_sum' => 'quote_actual_sum'
);