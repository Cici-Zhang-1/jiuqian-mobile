<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/wait_dismantle/edit'] = array(
	array(
		'field' => 'selected[]',
		'label' => '订单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);

$config['order/wait_dismantle/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '订单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
