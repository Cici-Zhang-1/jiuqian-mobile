<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
);
$config['order/order_product_board_model/select_board_predict'] = array(
	'opb_area' => 'area',
	'opb_board' => 'board',
	'op_num' => 'order_product_num',
	'op_product_id' => 'pid',
	'o_status' => 'status',
);
$config['order/order_product_board_model/select_dismantle_area'] = array(
	'sum(opb_area)' => 'area',
	'CONVERT(opb_board,SIGNED)' => 'board',
	'p_name' => 'name',
);
$config['order/order_product_board_model/select_dismantle_area_detail'] = array(
	'op_num' => 'num',
	'opb_area' => 'area',
	'opb_board' => 'board',
	'p_name' => 'name',
	'o_dismantled_datetime' => 'dismantled_datetime',
);
$config['order/order_product_board_model/select_by_oid'] = array(
	'opb_id' => 'opbid',
	'opb_board' => 'board',
	'p_code' => 'code',
	'op_id' => 'opid',
);