<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/money_factory/redelivery'] = array(
	array(
		'field' => 'selected[]',
		'label' => '到厂付款中重新发货的订单',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
	)
);
