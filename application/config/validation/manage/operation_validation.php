<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['manage/operation/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'parent',
		'label' => '父级',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
	),
	array(
		'field' => 'url',
		'label' => 'URL',
		'rules' => 'trim|min_length[1]|max_length[128]'
	)
);

$config['manage/operation/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '操作编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'parent',
		'label' => '父级',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
	),
	array(
		'field' => 'url',
		'label' => 'URI',
		'rules' => 'trim|min_length[1]|max_length[128]'
	)
);

$config['manage/operation/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '操作编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
	)
);
