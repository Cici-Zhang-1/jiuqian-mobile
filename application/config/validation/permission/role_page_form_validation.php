<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_page_form/edit'] = array(
	array(
		'field' => 'role_v',
		'label' => '角色',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'v[]',
		'label' => '页面表单权限',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
