<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/out_method/add'] = array(
	array(
		'field' => 'name',
		'label' => '出厂方式名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/out_method/edit'] = array(
	array(
		'field' => 'selected',
		'label' => 'selected',
		'rules' => 'required|numeric|min_length[1]|max_length[2]'
	),
	array(
		'field' => 'name',
		'label' => '出厂方式名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/out_method/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => 'selected',
		'rules' => 'required|numeric|min_length[1]|max_length[2]'
	)
);
