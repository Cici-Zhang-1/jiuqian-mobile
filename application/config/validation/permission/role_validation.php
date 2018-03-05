<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role/add'] = array(
	array(
		'field' => 'name',
		'label' => '角色名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['permission/role/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '角色编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'name',
		'label' => '角色名称',
		'rules' => 'trim|min_length[1]|max_length[64]'
	)
);

$config['permission/role/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '角色编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
