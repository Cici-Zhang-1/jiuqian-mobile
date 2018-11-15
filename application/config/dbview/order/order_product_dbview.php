<?php defined('BASEPATH') OR exit('No direct script access allowed');
$config['order/order_product_model/select'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_product_id' => 'product_id',
    'op_product' => 'product',
    'op_remark' => 'remark',
    'op_design_atlas' => 'design_atlas',
    'o_id' => 'order_id',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_checker' => 'checker',
    'o_checker_phone' => 'checker_phone',
    'o_remark' => 'order_remark',
    'C.u_truename' => 'creator',
    'D.u_truename' => 'dismantle',
    'op_dismantle_datetime' => 'dismantle_datetime',
    'wop_label' => 'status_label',
    'tl_icon' => 'icon'
);
$config['order/order_product_model/select_producing'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_product' => 'product',
    'op_remark' => 'remark',
    'o_id' => 'order_id',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_remark' => 'order_remark',
    'o_request_outdate' => 'request_outdate',
    'wop_label' => 'status_label',
    'tl_icon' => 'icon',
    'm_batch_num' => 'batch_num'
);
$config['order/order_product_model/select_by_order_id'] = array(
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'p_code' => 'code',
    'o_id' => 'order_id',
    'o_num' => 'order_num',
    'o_payterms' => 'payterms',
    'o_down_payment' => 'down_payment',
    'o_sum' => 'sum',
    'o_virtual_sum' => 'virtual_sum',
    'o_payed' => 'payed',
    'o_virtual_payed' => 'virtual_payed',
    'o_pay_status' => 'pay_status',
    'd_id' => 'dealer_id',
    'd_balance' => 'dealer_balance',
    'd_produce' => 'dealer_produce',
    'd_delivered' => 'dealer_delivered',
    'd_virtual_balance' => 'dealer_virtual_balance',
    'd_virtual_produce' => 'dealer_virtual_produce',
    'd_virtual_delivered' => 'dealer_virtual_delivered',
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
    'op_design_atlas' => 'design_atlas',
    'wop_label'=> 'status_label',
    'p_code' => 'code',
    'o_id' => 'order_id'
);
$config['order/order_product_model/select_post_sale'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_product_id' => 'product_id',
    'op_product' => 'product',
    'op_remark' => 'remark',
    'op_status' => 'status',
    'op_bd' => 'bd',
    'op_design_atlas' => 'design_atlas',
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
    'op_design_atlas' => 'design_atlas',
    'o_id' => 'order_id',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_request_outdate' => 'request_outdate',
    'o_remark' => 'order_remark',
    'wo_label' => 'status_label',
    'od_sure_datetime' => 'sure_datetime'
);

$config['order/order_product_model/select_produce_process_tracking'] = array(
    'op_id' => 'v',
    'op_num' => 'order_product_num',
    'op_product' => 'product',
    'op_producing_datetime' => 'producing_datetime',
    'o_id' => 'order_id',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'u_truename' => 'producing',
    'm_batch_num' => 'batch_num'
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

$config['order/order_product_model/select_work_out'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_product' => 'product',
    'op_pack' => 'pack',
    'op_pack_detail' => 'pack_detail',
    'op_delivered' => 'delivered',
    '(op_pack - op_delivered)' => 'wait_delivery',
    'op_remark' => 'remark',
    'o_id' => 'order_id',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_remark' => 'order_remark',
    'o_collection' => 'collection'
);

$config['order/order_product_model/select_warehouse_waiting_in'] = array(
    'op_id' => 'v',
    'op_num' => 'order_product_num',
    'op_pack_detail' => 'pack_detail',
    'op_inned_datetime' => 'inned_datetime',
    'o_dealer' => 'dealer'
);

$config['order/order_product_model/select_pick_sheet_detail'] = array(
    'op_id' => 'v',
    'op_num' => 'order_product_num',
    'A.scanned' => 'scanned',
    'convert(substr(op_num, 14), signed)' => 'no',
    'op_warehouse_num' => 'warehouse_v',
    'op_pack' => 'pack',
    'op_pack_detail' => 'pack_detail',
    'o_dealer' => 'dealer'
);

$config['order/order_product_model/select_pick_sheet_print'] = array(
    'op_id' => 'v',
    'op_num' => 'order_product_num',
    'A.scanned' => 'scanned',
    'convert(substr(op_num, 14), signed)' => 'no',
    'op_warehouse_num' => 'warehouse_v',
    'op_pack' => 'order_product_pack',
    'op_pack_detail' => 'pack_detail',
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
    'o_virtual_sum' => 'virtual_sum',
    'o_dealer_remark' => 'dealer_remark',
    'ps_label' => 'payed',
    'o_collection' => 'collection'
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
    'op_design_atlas' => 'design_atlas',
    'op_bd' => 'bd',
    'op_remark' => 'order_product_remark',
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
    'o_virtual_sum' => 'virtual_sum',
    'op_id' => array(
        'v',
        'order_product_id'
    ),
    'op_num' => 'order_product_num',
    'op_product_id' => 'product_id',
    'op_product' => 'product',
    'op_remark' => 'remark',
    'op_design_atlas' => 'design_atlas',
    'p_code' => 'code'
);

$config['order/order_product_model/is_order_post_salable'] = array(
    'o_id' => 'order_id',
    'o_num' => 'num',
    'o_dealer' => 'dealer',
    'o_dealer_id' => 'dealer_id',
    'o_owner' => 'owner',
    'o_status' => 'status',
    'o_sum' => 'sum',
    'o_sum_detail' => 'sum_detail',
    'o_virtual_sum' => 'virtual_sum',
    'o_payed' => 'payed',
    'o_pay_status' => 'pay_status',
    'op_id' => array(
        'v',
        'order_product_id'
    ),
    'op_num' => 'order_product_num',
    'op_product_id' => 'product_id',
    'op_product' => 'product',
    'op_remark' => 'remark',
    'op_design_atlas' => 'design_atlas',
    'op_sum' => 'order_product_sum',
    'p_code' => 'code',
    'd_balance' => 'dealer_balance',
    'd_produce' => 'dealer_produce',
    'd_delivered' => 'dealer_delivered',
    'd_virtual_balance' => 'dealer_virtual_balance',
    'd_virtual_produce' => 'dealer_virtual_produce',
    'd_virtual_delivered' => 'dealer_virtual_delivered',
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

$config['order/order_product_model/select_delivered'] = array(
    'op_order_id' => 'order_id',
    'sum(op_delivered)' => ' delivered'
);
$config['order/order_product_model/select_delivered_by_v'] = array(
    'op_id' => 'v',
    'op_order_id' => 'order_id',
    'op_delivered' => 'delivered'
);

$config['order/order_product_model/select_bd'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_product' => 'product',
    'op_remark' => 'remark',
    'u_truename' => 'dismantle',
    'o_remark' => 'order_remark'
);