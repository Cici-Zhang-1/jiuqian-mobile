<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/card/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'url',
		'label' => 'Url',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'card_type',
		'label' => '卡片类型',
		'rules' => 'trim|required|min_length[1]|max_length[32]'
	),
	array(
		'field' => 'card_setting',
		'label' => '设置方式',
		'rules' => 'trim|max_length[32]'
	)
);

$config['permission/card/edit'] = array(
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
		'field' => 'url',
		'label' => 'Url',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'card_type',
		'label' => '卡片类型',
		'rules' => 'trim|required|min_length[1]|max_length[32]'
	),
	array(
		'field' => 'card_setting',
		'label' => '设置方式',
		'rules' => 'trim|max_length[32]'
	)
);

$config['permission/card/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '卡片编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
