<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_visit/edit'] = array(
	array(
		'field' => 'rid',
		'label' => '角色',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'vid[]',
		'label' => '访问控制权限',
		'rules' => 'trim|min_length[1]|max_length[4]'
	)
);
