<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_card/edit'] = array(
	array(
		'field' => 'rid',
		'label' => '角色',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'cid[]',
		'label' => '卡片权限',
		'rules' => 'trim|min_length[1]|max_length[4]'
	)
);
