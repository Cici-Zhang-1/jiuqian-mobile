<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['sign/in'] = array(
	array(
		'field' => 'username',
		'label' => '用户名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'password',
		'label' => '登录密码',
		'rules' => 'trim|required|min_length[6]'
	)
);
