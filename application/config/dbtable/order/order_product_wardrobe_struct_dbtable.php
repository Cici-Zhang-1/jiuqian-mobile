<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_wardrobe_struct_model/update'] = array(
	'bid' => 'opws_board_id',
	'struct' => 'opws_struct',
);
$config['order/order_product_wardrobe_struct_model/insert'] = array(
	'opid' => 'opws_order_product_id',
	'bid' => 'opws_board_id',
	'struct' => 'opws_struct',
);