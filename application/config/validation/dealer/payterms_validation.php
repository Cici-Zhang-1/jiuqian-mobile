<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/payterms/add'] = array(
	array(
		'field' => 'name',
		'label' => '支付条款名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['dealer/payterms/edit'] = array(
	array(
		'field' => 'selected',
		'label' => 'selected',
		'rules' => 'required|numeric|min_length[1]|max_length[2]'
	),
	array(
		'field' => 'name',
		'label' => '支付条款名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);
