<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_form/edit'] = array(
	array(
		'field' => 'rid',
		'label' => '角色',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'fid[]',
		'label' => '表单权限',
		'rules' => 'trim|min_length[1]|max_length[4]'
	)
);
