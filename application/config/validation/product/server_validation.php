<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/server/add'] = array(
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
		'field' => 'unit',
		'label' => '单位',
		'rules' => 'trim|min_length[1]|max_length[8]'
	),
	array(
		'field' => 'unit_price',
		'label' => '单价',
		'rules' => 'trim|required|decimal|min_length[1]|max_length[10]'
	)
);

$config['product/server/edit'] = array(
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
		'field' => 'unit',
		'label' => '单位',
		'rules' => 'trim|min_length[1]|max_length[8]'
	),
	array(
		'field' => 'unit_price',
		'label' => '单价',
		'rules' => 'trim|required|decimal|min_length[1]|max_length[10]'
	)
);

$config['product/server/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
