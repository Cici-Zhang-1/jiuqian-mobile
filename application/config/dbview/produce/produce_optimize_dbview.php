<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['produce/produce_optimize_model/select_produce_optimize'] = array(
	'opb_id' => 'opbid',
	'opb_board' => 'board',
	'opb_area' => 'area',
	'opb_amount' => 'amount',
	'opb_optimize' => 'status',
	'opb_optimize_datetime' => 'optimize_datetime',
	'A.u_truename' => 'optimizer',
	'op_num' => 'order_product_num',
	'op_remark' => 'order_product_remark',
	'o_remark' => 'remark',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_request_outdate' => 'request_outdate',
	'o_asure_datetime' => 'asure_datetime',
	'B.u_truename' => 'dismantler',
);
$config['produce/produce_optimize_model/select_produce_optimize_download'] = array(
	'opbp_id' => 'opbpid',
	'opbp_qrcode' => 'qrcode',
	'opbp_cubicle_name' => 'cubicle_name',
	'opbp_cubicle_num' => 'cubicle_num',
	'opbp_plate_name' => 'plate_name',
	'opbp_plate_num' => 'plate_num',
	'opbp_width' => 'width',
	'opbp_length' => 'length',
	'opbp_thick' => 'thick',
	'opbp_slot' => 'slot',
	'opbp_punch' => 'punch',
	'opbp_edge' => 'edge',
	'opbp_remark' => 'remark',
	'if(opbp_abnormity = 0, "", "异")' => 'abnormity',
	'opbp_up_edge' => 'up_edge',
	'opbp_down_edge' => 'down_edge',
	'opbp_left_edge' => 'left_edge',
	'opbp_right_edge' => 'right_edge',
	'opb_board' => 'board',
	'opb_optimize' => 'status',
	'op_num' => 'order_product_num',
	'o_dealer' => 'dealers',
	'o_owner' => 'owner',
);