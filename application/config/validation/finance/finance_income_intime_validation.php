<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_income_intime/add'] = array(
	array(
		'field' => 'finance_account_id',
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
		'rules' => 'trim|decimal|decimal|greater_than_equal_to[0]|max_length[10]'
	),
	array(
		'field' => 'bank_date',
		'label' => '到账日期',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[512]'
	)
);

$config['finance/finance_income_intime/edit'] = array(
	array(
		'field' => 'v',
		'label' => '进账编号',
		'rules' => 'trim|required|numeric|max_length[10]'
	),
	array(
		'field' => 'finance_account_id',
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
		'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
	),
	array(
		'field' => 'bank_date',
		'label' => '到账日期',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[512]'
	)
);

$config['finance/finance_income_intime/remove'] = array(
	array(
		'field' => 'v[]',
		'label' => '进账编号',
		'rules' => 'trim|required|numeric|max_length[10]'
	)
);


$config['finance/finance_income_intime/claim'] = array(
    array(
        'field' => 'v',
        'label' => '进账编号',
        'rules' => 'trim|required|numeric|max_length[10]'
    ),
    array(
        'field' => 'amount',
        'label' => '进账金额',
        'rules' => 'trim|required|decimal|greater_than_equal_to[0]|max_length[10]'
    ),
    array (
        'field' => 'income_pay',
        'label' => '收入类型',
        'rules' => 'trim|max_length[64]'
    ),
    array (
        'field' => 'cargo_no',
        'label' => '货号',
        'rules' => 'trim|max_length[128]'
    ),
    array (
        'field' => 'dealer_id',
        'label' => '经销商',
        'rules' => 'trim|numeric|max_length[10]'
    ),
    array (
        'field' => 'dealer',
        'label' => '经销商',
        'rules' => 'trim|max_length[512]'
    ),
    array (
        'field' => 'corresponding',
        'label' => '货款金额',
        'rules' => 'trim|decimal|min_length[1]'
    ),
    array (
        'field' => 'remark',
        'label' => '备注',
        'rules' => 'trim|max_length[256]'
    )
);
