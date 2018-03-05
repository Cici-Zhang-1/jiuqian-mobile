<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/board_class/add'] = array(
	array(
		'field' => 'name',
		'label' => '板材环保等级',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	)
);

$config['data/board_class/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '板材环保等级',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	)
);

$config['data/board_class/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
