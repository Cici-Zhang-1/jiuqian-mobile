<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_delivery/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '发货信息编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);

$config['dealer/dealer_delivery/add'] = array(
	array(
		'field' => 'dealer_id',
		'label' => '经销商编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'daid',
		'label' => '地址',
		'rules' => 'trim|numeric|max_length[64]'
	),
	array(
		'field' => 'delivery_address',
		'label' => '街道',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'lid',
		'label' => '要求物流',
		'rules' => 'trim|required|numeric|max_length[4]'
	),
	array(
		'field' => 'omid',
		'label' => '出厂方式',
		'rules' => 'trim|required|numeric|max_length[2]'
	),
	array(
		'field' => 'delivery_linker',
		'label' => '联系人',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'delivery_phone',
		'label' => '联系方式',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'default',
		'label' => '类型',
		'rules' => 'trim|required|numeric|max_length[1]'
	)
);

$config['dealer/dealer_delivery/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '联系人编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'dealer_id',
		'label' => '经销商编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'daid',
		'label' => '地址',
		'rules' => 'trim|numeric|max_length[64]'
	),
	array(
		'field' => 'delivery_address',
		'label' => '街道',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'lid',
		'label' => '要求物流',
		'rules' => 'trim|required|numeric|max_length[4]'
	),
	array(
		'field' => 'omid',
		'label' => '出厂方式',
		'rules' => 'trim|required|numeric|max_length[2]'
	),
	array(
		'field' => 'delivery_linker',
		'label' => '联系人',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'delivery_phone',
		'label' => '联系方式',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'default',
		'label' => '类型',
		'rules' => 'trim|required|numeric|max_length[1]'
	)
);
