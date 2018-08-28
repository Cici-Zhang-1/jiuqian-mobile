<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['manage/myself/edit'] = array(
	array(
		'field' => 'v',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
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
		'field' => 'mobilephone',
		'label' => '移动电话',
		'rules' => 'trim|numeric|min_length[1]|max_length[16]'
	),
	array(
		'field' => 'password',
		'label' => '密码',
		'rules' => 'trim|min_length[6]|max_length[64]'
	)
);
