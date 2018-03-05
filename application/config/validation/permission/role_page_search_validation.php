<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_page_search/edit'] = array(
	array(
		'field' => 'rid',
		'label' => '角色',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'psid[]',
		'label' => '页面搜索权限',
		'rules' => 'trim|min_length[1]|max_length[4]'
	)
);
