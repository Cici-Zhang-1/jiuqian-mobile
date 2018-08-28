<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/edge/edit'] = array(
	array(
		'field' => 'v[]',
		'label' => '确认封边的订单',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
	)
);

$config['order/edge/correct'] = array(
    array(
        'field' => 'v[]',
        'label' => '需要校正的订单',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array(
        'field' => 'type',
        'label' => '类型',
        'rules' => 'trim|required|numeric|min_length[1]'
    ),
    array(
        'field' => 'edge',
        'label' => '封边人',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
