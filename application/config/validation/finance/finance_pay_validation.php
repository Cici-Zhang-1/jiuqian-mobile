<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_pay/add'] = array(
	array(
		'field' => 'faid',
		'label' => '账号',
		'rules' => 'trim|required|max_length[4]'
	),
	array(
		'field' => 'in_faid',
		'label' => '转入账号',
		'rules' => 'trim|numeric|max_length[4]'
	),
	array(
		'field' => 'type',
		'label' => '支出类型',
		'rules' => 'trim|required|max_length[128]'
	),
	array(
		'field' => 'amount',
		'label' => '支出金额',
		'rules' => 'trim|required|decimal|greater_than[0]|max_length[10]'
	),
	array(
		'field' => 'fee',
		'label' => '支出手续费',
		'rules' => 'trim|decimal|decimal|greater_than_equal_to[0]|max_length[10]'
	),
	array(
		'field' => 'bank_date',
		'label' => '到账日期',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'supplier_id',
		'label' => '供应商编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'supplier',
		'label' => '供应商名称',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[512]'
	)
);

$config['finance/finance_pay/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '进账编号',
		'rules' => 'trim|required|numeric|max_length[10]'
	),
	array(
		'field' => 'faid',
		'label' => '账号',
		'rules' => 'trim|required|max_length[4]'
	),
	array(
		'field' => 'in_faid',
		'label' => '转入账号',
		'rules' => 'trim|numeric|max_length[4]'
	),
	array(
		'field' => 'type',
		'label' => '支出类型',
		'rules' => 'trim|required|max_length[128]'
	),
	array(
		'field' => 'amount',
		'label' => '进账金额',
		'rules' => 'trim|required|decimal|greater_than[0]|max_length[10]'
	),
	array(
		'field' => 'amount',
		'label' => '进账手续费',
		'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
	),
	array(
		'field' => 'bank_date',
		'label' => '到账日期',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'supplier_id',
		'label' => '供应商编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'supplier',
		'label' => '供应商名称',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[512]'
	)
);

$config['finance/finance_pay/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '进账编号',
		'rules' => 'trim|required|numeric|max_length[10]'
	)
);
