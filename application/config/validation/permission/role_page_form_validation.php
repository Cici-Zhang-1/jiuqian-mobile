<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_page_form/edit'] = array(
	array(
		'field' => 'rid',
		'label' => '角色',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'pfid[]',
		'label' => '页面表单权限',
		'rules' => 'trim|min_length[1]|max_length[4]'
	)
);
