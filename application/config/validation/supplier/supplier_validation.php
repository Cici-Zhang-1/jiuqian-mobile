<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['supplier/supplier/add'] = array(
	array(
		'field' => 'code',
		'label' => '代号',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[128]'
	)
);

$config['supplier/supplier/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '供应商编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'code',
		'label' => '代号',
		'rules' => 'trim|required|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[128]'
	)
);

$config['supplier/supplier/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '供应商编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
