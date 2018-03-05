<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/func/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'mid',
		'label' => '菜单',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'url',
		'label' => 'URL',
		'rules' => 'trim|min_length[1]|max_length[256]'
	),
	array(
		'field' => 'displayorder',
		'label' => '显示顺序',
		'rules' => 'trim|numeric|min_length[0]|max_length[2]'
	),
	array(
		'field' => 'img',
		'label' => '图像',
		'rules' => 'trim|min_length[0]|max_length[128]'
	),
	array(
		'field' => 'group_no',
		'label' => '组号',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'toggle',
		'label' => 'Toggle',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'target',
		'label' => 'Target',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'tag',
		'label' => 'Tag',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'multiple',
		'label' => '多选',
		'rules' => 'trim|numeric|max_length[1]'
	)
);

$config['permission/func/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '功能编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'mid',
		'label' => '菜单',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'url',
		'label' => 'URL',
		'rules' => 'trim|min_length[1]|max_length[256]'
	),
	array(
		'field' => 'displayorder',
		'label' => '显示顺序',
		'rules' => 'trim|numeric|min_length[0]|max_length[2]'
	),
	array(
		'field' => 'img',
		'label' => '图像',
		'rules' => 'trim|min_length[0]|max_length[128]'
	),
	array(
		'field' => 'group_no',
		'label' => '组号',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'toggle',
		'label' => 'Toggle',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'target',
		'label' => 'Target',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'tag',
		'label' => 'Tag',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'multiple',
		'label' => '多选',
		'rules' => 'trim|numeric|max_length[1]'
	)
);

$config['permission/func/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '功能编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
