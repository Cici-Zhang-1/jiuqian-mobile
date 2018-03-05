<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/face/add'] = array(
	array(
		'field' => 'flag',
		'label' => '单双面标记',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'wardrobe_punch',
		'label' => '衣柜打孔名称',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'wardrobe_slot',
		'label' => '衣柜开槽名称',
		'rules' => 'trim|max_length[64]'
	)
);

$config['data/face/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'flag',
		'label' => '单双面标记',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'wardrobe_punch',
		'label' => '衣柜打孔名称',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'wardrobe_slot',
		'label' => '衣柜开槽名称',
		'rules' => 'trim|max_length[64]'
	)
);

$config['data/face/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
