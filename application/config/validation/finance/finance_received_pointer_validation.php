<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_received_pointer/add'] = array(
	array(
		'field' => 'frid',
		'label' => '进账编号',
		'rules' => 'trim|required|numeric|max_length[10]'
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
		'rules' => 'trim|required|max_length[512]'
	),
	array(
		'field' => 'order_num[]',
		'label' => '对应订单',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'amount',
		'label' => '账户到账',
		'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
	),
	array(
		'field' => 'corresponding',
		'label' => '对应货款',
		'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[512]'
	)
);
