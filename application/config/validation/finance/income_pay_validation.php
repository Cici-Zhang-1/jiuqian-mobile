<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/income_pay/add'] = array(
	array(
		'field' => 'type',
		'label' => '类型',
		'rules' => 'trim|required|max_length[16]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|max_length[64]'
	)
);

$config['finance/income_pay/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '收支类型编号',
		'rules' => 'trim|required|numeric|max_length[4]'
	),
	array(
		'field' => 'type',
		'label' => '类型',
		'rules' => 'trim|required|max_length[16]'
	),
	array(
		'field' => 'name',
		'label' => '财务账户名称',
		'rules' => 'trim|required|max_length[64]'
	)
);
