<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer/add'] = array(
	array(
		'field' => 'des',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'shop',
		'label' => '店名',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'aid',
		'label' => '地址',
		'rules' => 'trim|numeric|max_length[10]'
	),
	array(
		'field' => 'dcid',
		'label' => '类型',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
	),
	array(
		'field' => 'address',
		'label' => '街道',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'pid',
		'label' => '支付条款',
		'rules' => 'trim|required|is_natural_no_zero|max_length[2]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'delivery_linker',
		'label' => '联系人姓名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'delivery_phone',
		'label' => '联系人姓名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'daid',
		'label' => '收货地址',
		'rules' => 'trim|numeric|max_length[10]'
	),
	array(
		'field' => 'delivery_address',
		'label' => '收货地址详情',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'lid',
		'label' => '要求物流',
		'rules' => 'trim|numeric|max_length[4]'
	),
	array(
		'field' => 'omid',
		'label' => '出厂方式',
		'rules' => 'trim|numeric|max_length[2]'
	),
	array(
		'field' => 'name',
		'label' => '联系人姓名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'mobilephone',
		'label' => '移动电话',
		'rules' => 'trim|required|numeric|max_length[16]'
	),
	array(
		'field' => 'telephone',
		'label' => '固话',
		'rules' => 'trim|max_length[16]|gh_str_replace'
	),
	array(
		'field' => 'email',
		'label' => '邮箱',
		'rules' => 'trim|valid_email|max_length[128]'
	),
	array(
		'field' => 'qq',
		'label' => 'QQ',
		'rules' => 'trim|numeric|max_length[16]'
	),
	array(
		'field' => 'fax',
		'label' => 'Fax',
		'rules' => 'trim|numeric|max_length[16]|gh_str_replace'
	),
	array(
		'field' => 'doid',
		'label' => '员工类型',
		'rules' => 'trim|numeric|max_length[2]'
	)
);

$config['dealer/dealer/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '经销商编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'des',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'shop',
		'label' => '店名',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'aid',
		'label' => '地址',
		'rules' => 'trim|numeric|max_length[64]'
	),
	array(
		'field' => 'address',
		'label' => '街道',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'pid',
		'label' => '支付条款',
		'rules' => 'trim|required|is_natural_no_zero|max_length[2]'
	),
	array(
		'field' => 'dcid',
		'label' => '类型',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[2]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'password',
		'label' => '密码',
		'rules' => 'trim|max_length[128]'
	)
);

$config['dealer/dealer/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '经销商编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
