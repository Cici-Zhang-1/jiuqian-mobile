<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/account/add'] = array(
	array(
		'field' => 'name',
		'label' => '财务账户名称',
		'rules' => 'trim|required|max_length[64]'
	),
	array(
		'field' => 'account',
		'label' => '财务账号',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'host',
		'label' => '户主',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'fee',
		'label' => '费率',
		'rules' => 'trim|decimal|max_length[10]'
	),
	array(
		'field' => 'fee_max',
		'label' => '最高手续费',
		'rules' => 'trim|decimal|max_length[10]'
	),
	array(
		'field' => 'in',
		'label' => '收入',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'in_fee',
		'label' => '收入手续费',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'out',
		'label' => '支出',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'out_fee',
		'label' => '支出手续费',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'intime',
		'label' => '及时到账',
		'rules' => 'trim|numeric|max_length[1]'
	)
);

$config['finance/account/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '财务账户编号',
		'rules' => 'trim|required|numeric|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '财务账户名称',
		'rules' => 'trim|required|max_length[64]'
	),
	array(
		'field' => 'account',
		'label' => '财务账号',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'host',
		'label' => '户主',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'fee',
		'label' => '费率',
		'rules' => 'trim|decimal|max_length[10]'
	),
	array(
		'field' => 'fee_max',
		'label' => '最高手续费',
		'rules' => 'trim|decimal|max_length[10]'
	),
	array(
		'field' => 'in',
		'label' => '收入',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'in_fee',
		'label' => '收入手续费',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'out',
		'label' => '支出',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'out_fee',
		'label' => '支出手续费',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'intime',
		'label' => '及时到账',
		'rules' => 'trim|numeric|max_length[1]'
	)
);

$config['finance/account/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '财务账户编号',
		'rules' => 'trim|required|numeric|max_length[4]'
	)
);
