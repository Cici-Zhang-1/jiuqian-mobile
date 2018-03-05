<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/menu/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'label',
		'label' => 'Label',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'parent',
		'label' => '父级',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'url',
		'label' => 'URL',
		'rules' => 'trim|min_length[1]|max_length[128]'
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
		'field' => 'type',
		'label' => '类型',
		'rules' => 'trim|min_length[0]|max_length[32]'
	),
	array(
		'field' => 'mobile',
		'label' => '移动端显示',
		'rules' => 'trim|numeric|min_length[0]|max_length[1]'
	)
);

$config['permission/menu/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '菜单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'label',
		'label' => 'Label',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'parent',
		'label' => '父级',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'url',
		'label' => 'URI',
		'rules' => 'trim|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'displayorder',
		'label' => '显示顺序',
		'rules' => 'trim|numeric|min_length[0]|max_length[2]'
	),
	array(
		'field' => 'img',
		'label' => '图像',
		'rules' => 'trim|min_length[0]|max_length[128]|gh_str_replace'
	),
	array(
		'field' => 'type',
		'label' => '类型',
		'rules' => 'trim|min_length[0]|max_length[32]'
	),
	array(
		'field' => 'mobile',
		'label' => '移动端显示',
		'rules' => 'trim|numeric|min_length[0]|max_length[1]'
	)
);

$config['permission/menu/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '菜单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
