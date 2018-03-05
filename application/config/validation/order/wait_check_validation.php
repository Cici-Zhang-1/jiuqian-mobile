<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/wait_check/edit_asure'] = array(
	array(
		'field' => 'selected[]',
		'label' => '订单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
