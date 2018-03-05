<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/element/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'label',
		'label' => '标签',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'classes',
		'label' => '样式',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'displayorder',
		'label' => '显示顺序',
		'rules' => 'trim|numeric|max_length[1]'
	),
	array(
		'field' => 'checked',
		'label' => '默认',
		'rules' => 'trim|numeric|max_length[1]'
	)
);

$config['permission/element/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '卡片编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'label',
		'label' => '标签',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'classes',
		'label' => '样式',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'displayorder',
		'label' => '显示顺序',
		'rules' => 'trim|numeric|max_length[1]'
	),
	array(
		'field' => 'checked',
		'label' => '默认',
		'rules' => 'trim|numeric|max_length[1]'
	)
);

$config['permission/element/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '卡片编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
