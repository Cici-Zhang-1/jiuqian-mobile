<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/table_saw/edit'] = array(
	array(
		'field' => 'selected[]',
		'label' => '确认推台锯下料的订单',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
	)
);
