<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/produce/re_sure'] = array(
	array(
		'field' => 'v',
		'label' => '订单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
