<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_income_outtime/add'] = array(
	array(
		'field' => 'finance_account_id',
		'label' => '账号',
		'rules' => 'trim|required|numeric|greater_than[0]|max_length[10]'
	),
	array(
		'field' => 'amount',
		'label' => '进账金额',
		'rules' => 'trim|required|decimal|greater_than_equal_to[1]|max_length[10]'
	),
	array(
		'field' => 'fee',
		'label' => '进账手续费',
		'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
	),
	array(
		'field' => 'income_pay',
		'label' => '进账类型',
		'rules' => 'trim|required|max_length[64]'
	),
	array(
		'field' => 'dealer_id',
		'label' => '经销商编号',
		'rules' => 'trim|numeric|max_length[10]'
	),
	array(
		'field' => 'cargo_no',
		'label' => '货号',
		'rules' => 'trim|max_length[128]'
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
	),
    array(
        'field' => 'inned',
        'label' => '已到账',
        'rules' => 'trim|required|numeric|greater_than_equal_to[0]|less_than_equal_to[1]|max_length[1]'
    )
);

$config['finance/finance_income_outtime/edit'] = array(
    array(
        'field' => 'v',
        'label' => '进账编号',
        'rules' => 'trim|required|numeric|max_length[10]'
    ),
    array(
        'field' => 'finance_account_id',
        'label' => '账号',
        'rules' => 'trim|required|numeric|greater_than[0]|max_length[10]'
    ),
    array(
        'field' => 'amount',
        'label' => '进账金额',
        'rules' => 'trim|required|decimal|greater_than_equal_to[1]|max_length[10]'
    ),
    array(
        'field' => 'fee',
        'label' => '进账手续费',
        'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
    ),
    array(
        'field' => 'income_pay',
        'label' => '进账类型',
        'rules' => 'trim|required|max_length[64]'
    ),
    array(
        'field' => 'dealer_id',
        'label' => '经销商编号',
        'rules' => 'trim|numeric|max_length[10]'
    ),
    array(
        'field' => 'cargo_no',
        'label' => '货号',
        'rules' => 'trim|max_length[128]'
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
    ),
    array(
        'field' => 'inned',
        'label' => '已到账',
        'rules' => 'trim|required|numeric|greater_than_equal_to[0]|less_than_equal_to[1]|max_length[1]'
    )
);

$config['finance/finance_income_outtime/inned'] = array(
	array(
		'field' => 'v',
		'label' => '进账编号',
		'rules' => 'trim|required|numeric|max_length[10]'
	),
	array(
		'field' => 'fee',
		'label' => '进账手续费',
		'rules' => 'trim|decimal|greater_than_equal_to[0]|max_length[10]'
	)
);

$config['finance/finance_income_outtime/remove'] = array(
	array(
		'field' => 'v[]',
		'label' => '进账编号',
		'rules' => 'trim|required|numeric|max_length[10]'
	)
);
