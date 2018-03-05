<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/board_color/add'] = array(
	array(
		'field' => 'name',
		'label' => '板材颜色名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/board_color/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '板材颜色名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/board_color/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
