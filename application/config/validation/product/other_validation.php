<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/other/add'] = array(
	array(
		'field' => 'type',
		'label' => '类别',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'spec',
		'label' => '规格',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'unit',
		'label' => '单位',
		'rules' => 'trim|min_length[1]|max_length[8]'
	),
	array(
		'field' => 'supplier',
		'label' => '供应商',
		'rules' => 'trim|numeric|min_length[1]|max_length[4]'
	)
);

$config['product/other/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '配件编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'type',
		'label' => '类别',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'spec',
		'label' => '规格',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'unit',
		'label' => '单位',
		'rules' => 'trim|min_length[1]|max_length[8]'
	),
	array(
		'field' => 'supplier',
		'label' => '供应商',
		'rules' => 'trim|numeric|min_length[1]|max_length[4]'
	)
);

$config['product/other/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
