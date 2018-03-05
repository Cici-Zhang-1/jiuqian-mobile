<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['position/position/add'] = array(
	array(
		'field' => 'name',
		'label' => '库位名称',
		'rules' => 'trim|required|max_length[8]'
	)
);

$config['position/position/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '订单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '库位名称',
		'rules' => 'trim|required|max_length[8]'
	),
	array(
		'field' => 'status',
		'label' => '库位状态',
		'rules' => 'trim|required|numeric|max_length[1]'
	)
);

$config['position/position/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
