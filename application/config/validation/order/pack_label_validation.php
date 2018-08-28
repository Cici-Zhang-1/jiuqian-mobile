<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/pack_label/read'] = array(
	array(
		'field' => 'year',
		'label' => '年',
		'rules' => 'trim|required|numeric|min_length[4]|max_length[4]'
	),
    array(
        'field' => 'month',
        'label' => '月',
        'rules' => 'trim|required|numeric|greater_than[0]|less_than[13]|min_length[1]|max_length[2]'
    ),
    array(
        'field' => 'prefix',
        'label' => '大号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
    ),
    array(
        'field' => 'middle',
        'label' => '小号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array(
        'field' => 'code',
        'label' => '产品',
        'rules' => 'trim|strtoupper|required|regex_match[/^[WYMKPG]$/]'
    ),
    array(
        'field' => 'type',
        'label' => '订单类型',
        'rules' => 'trim|strtoupper|required|regex_match[/^[XB]$/]'
    )
);
$config['order/scan/correct'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
