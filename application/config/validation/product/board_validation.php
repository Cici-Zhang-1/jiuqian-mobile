<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/board/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'length',
		'label' => '长度',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'width',
		'label' => '宽度',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'thick_id',
		'label' => '厚度',
		'rules' => 'trim|required|min_length[1]|max_length[15]'
	),
	array(
		'field' => 'color_id[]',
		'label' => '颜色',
		'rules' => 'trim|required|min_length[1]|max_length[70]'
	),
	array(
		'field' => 'nature_id',
		'label' => '基材',
		'rules' => 'trim|required|min_length[1]|max_length[70]'
	),
	array(
		'field' => 'class_id',
		'label' => '环保等级',
		'rules' => 'trim|required|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'supplier_id',
		'label' => '供应商',
		'rules' => 'trim|required|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|min_length[1]|max_length[128]'
	)
);

$config['product/board/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'length',
		'label' => '长度',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'width',
		'label' => '宽度',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'thick_id',
		'label' => '厚度',
		'rules' => 'trim|required|min_length[1]|max_length[15]'
	),
	array(
		'field' => 'color_id[]',
		'label' => '颜色',
		'rules' => 'trim|required|min_length[1]|max_length[70]'
	),
	array(
		'field' => 'nature_id',
		'label' => '基材',
		'rules' => 'trim|required|min_length[1]|max_length[70]'
	),
	array(
		'field' => 'class_id',
		'label' => '环保等级',
		'rules' => 'trim|required|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'supplier_id',
		'label' => '供应商',
		'rules' => 'trim|required|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|min_length[1]|max_length[128]'
	)
);

$config['product/board/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
