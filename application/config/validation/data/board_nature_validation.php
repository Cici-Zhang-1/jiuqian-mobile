<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/board_nature/add'] = array(
	array(
		'field' => 'name',
		'label' => '板材材质名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/board_nature/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '板材材质名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/board_nature/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
