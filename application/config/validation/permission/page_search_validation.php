<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/page_search/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'label',
		'label' => 'Label',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'type',
		'label' => '类型',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'url',
		'label' => 'Url',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'mid',
		'label' => '菜单',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);

$config['permission/page_search/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '页面搜索编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'mid',
		'label' => '菜单',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'type',
		'label' => '类型',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'url',
		'label' => 'Url',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'label',
		'label' => 'Label',
		'rules' => 'trim|max_length[64]'
	)
);

$config['permission/page_search/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '页面搜索编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
