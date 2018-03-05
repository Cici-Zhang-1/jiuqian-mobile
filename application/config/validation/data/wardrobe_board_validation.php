<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/wardrobe_board/add'] = array(
	array(
		'field' => 'name',
		'label' => '衣柜板块名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/wardrobe_board/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '衣柜板块名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['data/wardrobe_board/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
