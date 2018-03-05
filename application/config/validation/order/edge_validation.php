<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/edge/edit'] = array(
	array(
		'field' => 'selected[]',
		'label' => '确认封边的订单',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
	)
);
