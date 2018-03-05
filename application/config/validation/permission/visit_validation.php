<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/visit/add'] = array(
	array(
		'field' => 'name',
		'label' => '访问控制名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'controller',
		'label' => '访问控制控制器',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'url',
		'label' => 'Url',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	)
);

$config['permission/visit/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '访问控制编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'name',
		'label' => '访问控制名称',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'controller',
		'label' => '访问控制控制器',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'url',
		'label' => 'Url',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	)
);

$config['permission/visit/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '访问控制编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
