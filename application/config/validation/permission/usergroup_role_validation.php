<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/usergroup_role/add'] = array(
	array(
		'field' => 'uid',
		'label' => '用户组',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'rid[]',
		'label' => '用户角色',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	)
);

$config['permission/usergroup_role/edit'] = array(
	array(
		'field' => 'uid',
		'label' => '用户组',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'rid[]',
		'label' => '用户角色',
		'rules' => 'trim|min_length[1]|max_length[4]'
	)
);
