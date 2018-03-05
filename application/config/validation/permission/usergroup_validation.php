<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/usergroup/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'class',
		'label' => '别名',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'parent',
		'label' => '名称',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);

$config['permission/usergroup/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '用户组编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'class',
		'label' => '别名',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'parent',
		'label' => '名称',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);

$config['permission/usergroup/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
