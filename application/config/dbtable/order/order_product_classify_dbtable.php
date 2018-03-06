<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_classify_model/insert'] = array(
	'opid' => 'opc_order_product_id',
	'board' => 'opc_board',
	'classify_id' => 'opc_classify_id',
	'status' => 'opc_status',
	'optimize' => 'opc_optimize',
);
$config['order/order_product_classify_model/update'] = array(
	'amount' => 'opc_amount',
	'area' => 'opc_area',
	'optimize' => 'opc_optimize',
);
$config['order/order_product_classify_model/update_batch'] = array(
	'opcid' => 'opc_id',
	'amount' => 'opc_amount',
	'area' => 'opc_area',
	'optimize' => 'opc_optimize',
);
$config['order/order_product_classify_model/update_workflow'] = array(
	'status' => 'opc_status',
	'sn' => 'opc_sn',
	'optimizer' => 'opc_optimizer',
	'optimize_datetime' => 'opc_optimize_datetime',
);