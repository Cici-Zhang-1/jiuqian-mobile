<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_board_model/select'] = array(
    'opb_id' => 'v',
    'opb_board' => array('board', 'name'),
    'opb_amount' => 'amount',
    'opb_area' => 'area',
    'opb_virtual_area' => 'virtual_area',
    'opb_purchase' => 'purchase',
    'opb_unit_price' => 'unit_price',
    'opb_sum' => 'sum',
    'opb_virtual_sum' => 'virtual_sum',
    'opb_open_hole' => 'open_hole',
    'opb_open_hole_unit_price' => 'open_hole_unit_price',
    'opb_invisibility' => 'invisibility',
    'opb_invisibility_unit_price' => 'invisibility_unit_price',
    'opb_area_diff' => 'area_diff',
    'opb_sum_diff' => 'sum_diff',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'op_product' => 'product',
    'op_remark' => 'order_product_remark',
    'op_pack' => 'pack',
    'op_warehouse_num' => 'warehouse_num',
    'wop_label' => 'status_label',
    'p_code' => 'code',
    'op_order_id' => 'order_id'
);

$config['order/order_product_board_model/has_brothers'] = array(
    'opb_id' => 'v',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'op_remark' => 'remark',
    'op_product' => 'product',
    'op_product_id' => 'product_id',
    'o_id' => 'order_id',
    'o_remark' => 'order_remark',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_request_outdate' => 'request_outdate'
);

$config['order/order_product_board_model/select_for_sure'] = array(
    'opb_id' => 'v'
);

$config['order/order_product_board_model/are_to_able'] = array(
    'opb_order_product_id' => 'order_product_id'
);

$config['order/order_product_board_model/is_exist'] = array(
	'opb_id' => 'opbid',
	'opb_area' => 'area',
	'opb_amount' => 'amount',
);

$config['order/order_product_board_model/select_optimize'] = array(
	'opb_id' => 'opbid',
	'opb_board' => 'board',
	'opb_area' => 'area',
	'opb_amount' => 'amount',
	'opb_optimize' => 'status',
	'opb_optimize_datetime' => 'optimize_datetime',
	'A.u_truename' => 'optimizer',
	'op_num' => 'order_product_num',
	'op_remark' => 'order_product_remark',
	'op_product' => 'product',
	'o_remark' => 'remark',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_request_outdate' => 'request_outdate',
	'o_asure_datetime' => 'asure_datetime',
	'B.u_truename' => 'dismantler',
);
$config['order/order_product_board_model/select_produce'] = array(
	'opb_id' => 'opbid',
	'opb_board' => 'board',
	'opb_area' => 'area',
	'opb_open_hole' => 'open_hole',
	'opb_invisibility' => 'invisibility',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_request_outdate' => 'request_outdate',
	'o_asure_datetime' => 'asure_datetime',
	'op_num' => 'order_product_num',
);
$config['order/order_product_board_model/select_status'] = array(
	'opb_id' => 'id',
	'opb_status' => 'status',
);
$config['order/order_product_board_model/select_check_by_opid'] = array(
	'opb_id' => 'opbid',
	'op_id' => 'opid',
	'opb_board' => 'board',
	'opb_amount' => 'amount',
	'opb_area' => 'area',
	'opb_area_diff' => 'area_diff',
	'if(opb_unit_price = 0, b_unit_price, opb_unit_price)' => 'unit_price',
	'opb_sum' => 'sum',
	'opb_sum_diff' => 'sum_diff',
    'opb_virtual_area' => 'virtual_area',
    'opb_virtual_sum' => 'virtual_sum',
	'opb_invisibility' => 'invisibility',
	'opb_open_hole' => 'open_hole',
	'opb_invisibility_unit_price' => 'invisibility_unit_price',
	'opb_open_hole_unit_price' => 'open_hole_unit_price',
	'op_num' => 'order_product_num',
	'op_remark' => 'remark',
);
$config['order/order_product_board_model/select_order_product_board_by_opid'] = array(
	'opb_id' => 'opbid',
	'opb_board' => 'board',
	'opb_amount' => 'amount',
	'opb_area' => 'area',
	'opb_unit_price' => 'unit_price',
	'opb_sum' => 'sum',
    'opb_virtual_area' => 'virtual_area',
    'opb_virtual_sum' => 'virtual_sum'
);
$config['order/order_product_board_model/select_board_predict'] = array(
	'opb_area' => 'area',
	'opb_board' => 'board',
	'b_thick' => 'thick',
	'op_num' => 'order_product_num',
	'op_product_id' => 'product_id',
	'o_status' => 'status',
);
$config['order/order_product_board_model/select_dismantled'] = array(
    'opb_id' => 'v',
	'opb_board' => 'board',
	'opb_area' => 'area',
	'b_thick' => 'thick',
	'op_id' => 'order_product_id',
	'op_num' => 'order_product_num',
	'op_dismantle_datetime' => 'dismantle_datetime',
	'p_id' => 'product_id',
	'p_name' => 'product'
);

$config['order/order_product_board_model/select_by_oid'] = array(
	'opb_id' => 'opbid',
	'opb_board' => 'board',
	'p_code' => 'code',
	'op_id' => 'opid',
);

$config['order/order_product_board_model/select_edge'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board',
    'opb_area' => 'area',
    'op_num' => 'order_product_num',
    'wopb_label' => 'status',
    'u_truename' => 'creator',
    'wopbm_create_datetime' => 'create_datetime'
);

$config['order/order_product_board_model/select_next_edge'] = array(
    'opb_id' => 'v'
);
$config['order/order_product_board_model/select_next_scan'] = array(
    'opb_id' => 'v'
);
$config['order/order_product_board_model/select_next_pack'] = array(
    'opb_id' => 'v'
);

$config['order/order_product_board_model/select_produce_process'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board',
    'sum(opb_area)' => 'area',
    'op_num' => 'order_product_num',
    'wopb_label' => 'status',
    'u_truename' => 'creator',
    'wopbm_create_datetime' => 'create_datetime',
    'p_name' => 'product',
    'o_order_type' => 'order_type'
);

$config['order/order_product_board_model/select_sscan'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board',
    'opb_area' => 'area',
    'op_num' => 'order_product_num',
    'wopb_label' => 'status',
    'u_truename' => 'creator',
    'wopbm_create_datetime' => 'create_datetime'
);

$config['order/order_product_board_model/select_ppack'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board',
    'opb_area' => 'area',
    'op_num' => 'order_product_num',
    'wopb_label' => 'status',
    'u_truename' => 'creator',
    'wopbm_create_datetime' => 'create_datetime'
);

$config['order/order_product_board_model/select_produce_process_by_order_product_v'] = array(
    'opb_id' => 'v'
);

$config['order/order_product_board_model/select_order_product_v_by_v'] = array(
    'opb_order_product_id' => 'order_product_v'
);

$config['order/order_product_board_model/select_order_product_id'] = array(
    'opb_order_product_id' => 'order_product_id'
);

$config['order/order_product_board_model/select_current_workflow'] = array(
    'wp_id' => 'v',
    'wp_name' => 'name',
    'wp_label' => 'label',
    'wp_file' => 'file'
);

$config['order/order_product_board_model/select_only_guiti'] = array(
    'opb_id' => 'v'
);

$config['order/order_product_board_model/is_edging'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board'
);
$config['order/order_product_board_model/is_status'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board'
);
$config['order/order_product_board_model/is_status_and_brothers'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board'
);
$config['order/order_product_board_model/are_edged_and_brothers'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board'
);
$config['order/order_product_board_model/are_scanned_and_brothers'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board'
);
$config['order/order_product_board_model/had_status_and_brothers'] = array(
    'opb_id' => 'v',
    'opb_board' => 'board',
    'u_truename' => 'creator',
    'wopbm_id' => 'workflow_order_product_board_msg_v',
    'wopbm_msg' => 'msg'
);
$config['order/order_product_board_model/select_user_current_work'] = array(
    'opb_id' => 'v'
);

$config['order/order_product_board_model/select_packable_by_order_product_id'] = array(
    'opb_id' => 'v',
    'opb_status' => 'status',
    'opb_procedure' => 'procedure'
);

$config['order/order_product_board_model/select_sales'] = array(
    'opb_id' => 'v',
    'opb_sum' => 'sum',
    'opb_area' => 'area',
    'opb_virtual_area' => 'virtual_area',
    'opb_virtual_sum' => 'virtual_sum',
    'o_dealer_id' => 'dealer_id',
    'o_dealer' => 'dealer',
    'b_thick' => 'thick',
    'b_nature' => 'nature',
    'b_color' => 'color',
    'p_id' => 'product_id',
    'p_name' => 'product'
);