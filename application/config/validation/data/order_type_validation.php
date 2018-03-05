<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/order_type/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[16]'
	),
	array(
		'field' => 'code',
		'label' => '代号',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	)
);

$config['data/order_type/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[2]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[16]'
	),
	array(
		'field' => 'code',
		'label' => '代号',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	)
);
