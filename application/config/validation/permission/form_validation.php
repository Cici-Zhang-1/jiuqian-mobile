<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/form/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'label',
		'label' => 'Label',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'type',
		'label' => '类型',
		'rules' => 'trim|min_length[1]|max_length[32]'
	),
	array(
		'field' => 'url',
		'label' => 'Url',
		'rules' => 'trim|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'func_id',
		'label' => '功能',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);

$config['permission/form/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '表单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'label',
		'label' => 'Label',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'type',
		'label' => '类型',
		'rules' => 'trim|min_length[1]|max_length[32]'
	),
	array(
		'field' => 'url',
		'label' => 'Url',
		'rules' => 'trim|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'func_id',
		'label' => '功能',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);

$config['permission/form/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '表单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
