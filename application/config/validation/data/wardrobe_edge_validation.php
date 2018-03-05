<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/wardrobe_edge/add'] = array(
	array(
		'field' => 'name',
		'label' => '衣柜封边名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'ups',
		'label' => '上',
		'rules' => 'trim|floatval|max_length[5]'
	),
	array(
		'field' => 'downs',
		'label' => '下',
		'rules' => 'trim|floatval|max_length[5]'
	),
	array(
		'field' => 'lefts',
		'label' => '左',
		'rules' => 'trim|floatval|max_length[5]'
	),
	array(
		'field' => 'rights',
		'label' => '右',
		'rules' => 'trim|floatval|max_length[5]'
	)
);

$config['data/wardrobe_edge/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '衣柜封边名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'ups',
		'label' => '上',
		'rules' => 'trim|floatval|max_length[5]'
	),
	array(
		'field' => 'downs',
		'label' => '下',
		'rules' => 'trim|floatval|max_length[5]'
	),
	array(
		'field' => 'lefts',
		'label' => '左',
		'rules' => 'trim|floatval|max_length[5]'
	),
	array(
		'field' => 'rights',
		'label' => '右',
		'rules' => 'trim|floatval|max_length[5]'
	)
);

$config['data/wardrobe_edge/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
