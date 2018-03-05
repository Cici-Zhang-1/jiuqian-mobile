<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/board_thick/add'] = array(
	array(
		'field' => 'name',
		'label' => '板材厚度',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);

$config['data/board_thick/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '板材厚度',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);

$config['data/board_thick/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
