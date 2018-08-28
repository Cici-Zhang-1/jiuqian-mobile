<?php defined('BASEPATH') OR exit('No direct script access allowed');
$config['order/order_product_model/select'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_product_id' => 'product_id',
    'op_product' => 'product',
    'op_remark' => 'remark',
    'o_id' => 'order_id',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_checker' => 'checker',
    'o_checker_phone' => 'checker_phone',
    'o_remark' => 'order_remark',
    'C.u_truename' => 'creator',
    'D.u_truename' => 'dismantle',
    'op_dismantle_datetime' => 'dismantle_datetime',
    'wop_label' => 'status_label'
);
$config['order/order_product_model/select_by_order_id'] = array(
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'p_code' => 'code',
    'o_id' => 'order_id',
    'o_payterms' => 'payterms',
    'o_down_payment' => 'down_payment',
    'o_sum' => 'sum',
    'o_payed' => 'payed',
    'o_pay_status' => 'pay_status',
    'd_balance' => 'dealer_balance',
    'd_produce' => 'dealer_produce',
    'd_delivered' => 'dealer_delivered',
    'o_status' => 'status'
);
$config['order/order_product_model/select_by_v'] = array(
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'p_code' => 'code'
);
$config['order/order_product_model/select_order_id_by_order_product_id'] = array(
    'o_id' => 'order_id',
    'o_status' => 'status'
);
$config['order/order_product_model/select_dismantle'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_product_id' => 'product_id',
    'op_product' => 'product',
    'op_remark' => 'remark',
    'op_status' => 'status',
    'op_bd' => 'bd',
    'wop_label'=> 'status_label',
    'p_code' => 'code',
    'o_id' => 'order_id'
);

$config['order/order_product_model/select_detail'] = array(
    'op_id' => array(
        'order_prodcut_id',
        'v'
    ),
    'op_num' => 'num',
    'op_product' => 'product',
    'op_remark' => 'remark',
    'o_id' => 'order_id',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_request_outdate' => 'request_outdate',
    'o_remark' => 'order_remark',
    'wo_label' => 'status_label',
    'od_sure_datetime' => 'sure_datetime'
);

$config['order/order_product_model/select_brothers'] = array(
    'op_id' => 'v',
    'op_num' => 'order_product_num',
    'op_pack_detail' => 'pack_detail'
);

$config['order/order_product_model/select_pack'] = array(
    'op_id' => 'v',
    'op_num' => 'order_product_num',
    'op_product' => 'product',
    'op_scan_start' => 'start_date',
    'op_scan_end' => 'end_date',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_remark' => 'remark',
    'u_truename' => 'creator',
    'ss_label' => 'status'
);

$config['order/order_product_model/select_packed'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_product' => 'product',
    'op_pack_detail' => 'pack_detail',
    'op_remark' => 'remark',
    'o_id' => 'order_id',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_remark' => 'order_remark',
    'o_request_outdate' => 'request_outdate'
);

$config['order/order_product_model/is_all_inned'] = array(
    'op_id' => 'v',
    'op_pack' => 'pack',
    'op_pack_detail' => 'pack_detail',
    'op_status' => 'status',
    'p_code' => 'code'
);

$config['order/order_product_model/select_warehouse_waiting_in'] = array(
    'op_id' => 'v',
    'op_num' => 'order_product_num',
    'op_pack_detail' => 'pack_detail',
    'op_pack_datetime' => 'pack_datetime',
    'o_dealer' => 'dealer'
);

$config['order/order_product_model/select_pick_sheet_detail'] = array(
    'op_id' => 'v',
    'op_num' => 'order_product_num',
    'A.scanned' => 'scanned',
    'convert(substr(op_num, 14), signed)' => 'no',
    'op_warehouse_num' => 'warehouse_v',
    'op_pack_detail' => 'pack_detail',
    'op_pack_datetime' => 'pack_datetime',
    'o_dealer' => 'dealer',
);

$config['order/order_product_model/select_pick_sheet_print'] = array(
    'op_id' => 'v',
    'op_num' => 'order_product_num',
    'A.scanned' => 'scanned',
    'convert(substr(op_num, 14), signed)' => 'no',
    'op_warehouse_num' => 'warehouse_v',
    'op_pack' => 'order_product_pack',
    'op_pack_detail' => 'pack_detail',
    'op_pack_datetime' => 'pack_datetime',
    'op_product' => 'product',
    'o_id' => 'order_v',
    'o_dealer' => 'dealer',
    'o_dealer_id' => 'did',
    'o_delivery_area' => 'delivery_area',
    'o_delivery_address' => 'delivery_address',
    'o_delivery_linker' => 'delivery_linker',
    'o_delivery_phone' => 'delivery_phone',
    'o_logistics' => 'logistics',
    'o_owner' => 'owner',
    'o_sum' => 'sum',
    'if(o_payed_datetime is not null && o_payed_datetime > 0, "已付", o_payterms)' => 'payed',
    'so_end_datetime' => 'end_datetime',
    'so_truck' => 'truck',
    'so_train' => 'train',
    'so_pack' => 'pack',
    'so_collection' => 'collection'
);

$config['order/order_product_model/select_current_workflow'] = array(
    'wop_id' => 'v',
    'wop_name' => 'name',
    'wop_label' => 'label',
    'wop_file' => 'file'
);

$config['order/order_product_model/is_exist'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_product' => 'product',
    'op_pack' => 'pack',
    'op_pack_detail' => 'pack_detail',
    'op_status' => 'status',
    'op_warehouse_num' => 'order_product_warehouse_num',
    'p_name' => 'product_name',
    'p_code' => 'code',
    'o_id' => array(
        'order_v',
        'order_id'
    ),
    'o_warehouse_num' => 'warehouse_v',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_remark' => 'remark',
    'o_delivery_area' => 'delivery_area',
    'o_delivery_address' => 'delivery_address',
    'o_delivery_linker' => 'delivery_linker',
    'o_delivery_phone' => 'delivery_phone'
);

$config['order/order_product_model/is_dismantlable'] = array(
    'op_id' => 'order_product_id',
    'op_product_id' => 'product_id',
    'op_order_id' => 'order_id',
    'p_code' => 'code'
);

$config['order/order_product_model/is_order_dismantlable'] = array(
    'o_id' => 'order_id',
    'o_num' => 'num',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_status' => 'status',
    'o_sum' => 'sum',
    'op_id' => 'order_product_id',
    'op_product_id' => 'product_id',
    'op_product' => 'product',
    'op_remark' => 'remark',
    'p_code' => 'code'
);

$config['order/order_product_model/are_dismantlable'] = array(
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'op_order_id' => 'order_id',
    'op_product_id' => 'product_id',
    'p_code' => 'code'
);

$config['order/order_product_model/are_status'] = array(
    'op_id' => 'order_product_id'
);