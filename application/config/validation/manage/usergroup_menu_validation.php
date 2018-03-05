<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['manage/usergroup_menu/edit'] = array(
	array(
		'field' => 'ugid',
		'label' => '用户组编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'pid[]',
		'label' => '用户组菜单权限编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
