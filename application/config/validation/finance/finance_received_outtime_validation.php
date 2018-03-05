<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_received_outtime/add'] = array(
	array(
		'field' => 'faid',
		'label' => '账号',
		'rules' => 'trim|required|max_length[64]'
	),
	array(
		'field' => 'amount',
		'label' => '进账金额',
		'rules' => 'trim|required|decimal|greater_than_equal_to[0]|max_length[10]'
	),
	array(
		'field' => 'fee',
		'label' => '进账手续费',
		'rules' => 'trim|floatval|decimal|greater_than_equal_to[0]|max_length[10]'
	),
	array(
		'field' => 'type',
		'label' => '进账类型',
		'rules' => 'trim|required|max_length[64]'
	),
	array(
		'field' => 'did',
		'label' => '经销商编号',
		'rules' => 'trim|intval|max_length[10]'
	),
	array(
		'field' => 'dealer',
		'label' => '经销商',
		'rules' => 'trim|max_length[512]'
	),
	array(
		'field' => 'order_num[]',
		'label' => '对应订单',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'cargo_no',
		'label' => '货号',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'corresponding',
		'label' => '对应货款',
		'rules' => 'trim|floatval|decimal|greater_than_equal_to[0]|max_length[10]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[512]'
	)
);

$config['finance/finance_received_outtime/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '进账编号',
		'rules' => 'trim|required|numeric|max_length[10]'
	),
	array(
		'field' => 'fee',
		'label' => '进账手续费',
		'rules' => 'trim|floatval|decimal|greater_than_equal_to[0]|max_length[10]'
	)
);

$config['finance/finance_received_outtime/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '进账编号',
		'rules' => 'trim|required|numeric|max_length[10]'
	)
);
