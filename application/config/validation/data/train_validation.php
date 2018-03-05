<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/train/add'] = array(
	array(
		'field' => 'name',
		'label' => '车次名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/train/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '车次名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/train/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
