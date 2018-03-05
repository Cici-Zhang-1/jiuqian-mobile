<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/cnc/edit'] = array(
	array(
		'field' => 'selected[]',
		'label' => '确认CNC的订单',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
	)
);
