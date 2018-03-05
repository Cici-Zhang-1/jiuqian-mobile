<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/area/add'] = array(
	array(
		'field' => 'province',
		'label' => '省',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'city',
		'label' => '市',
		'rules' => 'trim|required|max_length[32]'
	),
	array(
		'field' => 'county',
		'label' => '县/区',
		'rules' => 'trim|max_length[32]'
	)
);

$config['data/area/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'province',
		'label' => '省',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'city',
		'label' => '市',
		'rules' => 'trim|required|max_length[32]'
	),
	array(
		'field' => 'county',
		'label' => '县/区',
		'rules' => 'trim|max_length[32]'
	)
);

$config['data/area/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
