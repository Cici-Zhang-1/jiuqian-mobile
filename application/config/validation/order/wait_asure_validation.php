<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/wait_asure/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '订单编号',
		'rules' => 'trim|required'
	),
	array(
		'field' => 'request_outdate',
		'label' => '要求出厂日期',
		'rules' => 'trim|required'
	)
);
