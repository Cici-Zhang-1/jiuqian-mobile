<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/workflow/add'] = array(
	array(
		'field' => 'no',
		'label' => '编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'type',
		'label' => '类型',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'name_en',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'file',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/workflow/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'no',
		'label' => '流程节点编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'type',
		'label' => '类型',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'name_en',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'file',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/workflow/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	)
);
