<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/logistics/add'] = array(
	array(
		'field' => 'name',
		'label' => '物流名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'aid',
		'label' => '地址',
		'rules' => 'trim|numeric|max_length[10]'
	),
	array(
		'field' => 'address',
		'label' => '街道',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'phone',
		'label' => '联系方式',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'vip',
		'label' => 'vip号',
		'rules' => 'trim|max_length[64]'
	)
);

$config['data/logistics/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '物流名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'aid',
		'label' => '地址',
		'rules' => 'trim|numeric|max_length[10]'
	),
	array(
		'field' => 'address',
		'label' => '街道',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'phone',
		'label' => '联系方式',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'vip',
		'label' => 'vip号',
		'rules' => 'trim|max_length[64]'
	)
);

$config['data/logistics/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
