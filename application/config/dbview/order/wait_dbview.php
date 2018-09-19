<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/wait_check/read'] = array(
	'o_id' => 'oid',
	'o_num' => 'order_num',
	'o_dealer_id' => 'did',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_remark' => 'remark',
	'o_request_outdate' => 'request_outdate',
	'o_dismantled_datetime' => 'dismantled_datetime',
	'o_sum' => 'sum',
	'o_sum_detail' => 'sum_detail',
    'o_virtual_sum' => 'virtual_sum',
	'tl_icon' => 'icon',
);
$config['order/wait_quote/read'] = array(
	'o_id' => 'oid',
	'o_num' => 'order_num',
	'o_dealer_id' => 'did',
	'o_dealer' => 'dealer',
	'o_payterms' => 'payterms',
	'o_payer' => 'payer',
	'o_payer_phone' => 'payer_phone',
	'o_owner' => 'owner',
	'o_remark' => 'remark',
	'o_request_outdate' => 'request_outdate',
	'o_checked_datetime' => 'checked_datetime',
	'o_sum' => 'sum',
    'o_virtual_sum' => 'virtual_sum',
	'd_debt1' => 'debt1',
	'd_debt2' => 'debt2',
	'd_balance' => 'balance',
    'd_virtual_balance' => 'virtual_balance',
	'tl_icon' => 'icon',
);
$config['order/wait_asure/read'] = array(
	'o_id' => 'oid',
	'o_num' => 'order_num',
	'o_dealer_id' => 'did',
	'o_dealer' => 'dealer',
	'o_owner' => 'owner',
	'o_remark' => 'remark',
	'o_request_outdate' => 'request_outdate',
	'o_quoted_datetime' => 'quoted_datetime',
	'o_payed_datetime' => 'payed_datetime',
	'o_asure_datetime' => 'asure_datetime',
	'tl_icon' => 'icon',
);