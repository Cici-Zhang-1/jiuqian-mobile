<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_menu/edit'] = array(
	array(
		'field' => 'v[]',
		'label' => '菜单V',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'role_v',
		'label' => '角色',
		'rules' => 'trim|numeric|min_length[1]|max_length[4]'
	)
);
