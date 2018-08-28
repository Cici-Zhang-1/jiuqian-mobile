<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/delivered/redelivery'] = array(
	array(
		'field' => 'selected[]',
		'label' => '重新发货的订单',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
	)
);
