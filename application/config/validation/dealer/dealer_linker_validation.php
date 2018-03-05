<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_linker/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '供应商联系人编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);

$config['dealer/dealer_linker/add'] = array(
	array(
		'field' => 'dealer_id',
		'label' => '经销商编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '姓名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'primary',
		'label' => '首要联系人',
		'rules' => 'trim|required|numeric|max_length[1]'
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
		'label' => '地址',
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
		'label' => '联系人类型',
		'rules' => 'trim|numeric|max_length[2]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[128]'
	)
);

$config['dealer/dealer_linker/edit'] = array(
	array(
		'field' => 'selected[]',
		'label' => '联系人编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '姓名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'mobilephone',
		'label' => '移动电话',
		'rules' => 'trim|numeric|max_length[16]'
	),
	array(
		'field' => 'telephone',
		'label' => '固话',
		'rules' => 'trim|max_length[64]|gh_str_replace'
	),
	array(
		'field' => 'email',
		'label' => '地址',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'qq',
		'label' => 'QQ',
		'rules' => 'trim|numeric|max_length[16]'
	),
	array(
		'field' => 'oid',
		'label' => '联系人类型',
		'rules' => 'trim|numeric|max_length[2]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[128]'
	)
);
