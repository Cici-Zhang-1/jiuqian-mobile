<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/product/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[16]'
	),
	array(
		'field' => 'code',
		'label' => '代号',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|min_length[1]|max_length[128]'
	)
);

$config['product/product/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[16]'
	),
	array(
		'field' => 'code',
		'label' => '代号',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|min_length[1]|max_length[128]'
	)
);

$config['product/product/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
