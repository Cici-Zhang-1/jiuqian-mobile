<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/wait_sure/add'] = array(
	array(
		'field' => 'v[]',
		'label' => '订单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'request_outdate',
		'label' => '要求出厂日期',
		'rules' => 'trim|required|max_length[64]|valid_date'
	)
);

$config['order/wait_sure/edit'] = array(
    array(
        'field' => 'v',
        'label' => '订单编号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array(
        'field' => 'down_payment',
        'label' => '首付比例',
        'rules' => 'trim|required|decimal|max_length[5]'
    )
);

$config['order/wait_sure/re_sure'] = array(
    array(
        'field' => 'v',
        'label' => '订单编号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
