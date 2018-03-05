<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/task_level/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'icon',
		'label' => 'icon',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|min_length[1]|max_length[64]'
	)
);

$config['data/task_level/edit'] = array(
	array(
		'field' => 'selected',
		'label' => 'selected',
		'rules' => 'required|numeric|min_length[1]|max_length[2]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'icon',
		'label' => 'icon',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|min_length[1]|max_length[64]'
	)
);

$config['data/task_level/remove'] = array(
	array(
		'field' => 'selected',
		'label' => 'selected',
		'rules' => 'required|numeric|min_length[1]|max_length[2]'
	)
);
