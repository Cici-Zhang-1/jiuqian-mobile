<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['manage/user/add'] = array(
	array(
		'field' => 'name',
		'label' => '用户名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'truename',
		'label' => '真实姓名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'password',
		'label' => '密码',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'mobilephone',
		'label' => '移动电话',
		'rules' => 'trim|numeric|min_length[0]|max_length[16]'
	),
	array(
		'field' => 'ugid',
		'label' => '用户组',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);

$config['manage/user/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '用户编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'name',
		'label' => '用户名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'truename',
		'label' => '真实姓名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'password',
		'label' => '密码',
		'rules' => 'trim|max_length[16]'
	),
	array(
		'field' => 'mobilephone',
		'label' => '移动电话',
		'rules' => 'trim|numeric|min_length[0]|max_length[16]'
	),
	array(
		'field' => 'ugid',
		'label' => '用户组',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);

$config['manage/user/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '用户编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
