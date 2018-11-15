<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/pack/edit'] = array(
	array(
		'field' => 'v[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
$config['order/pack/correct'] = array(
    array(
        'field' => 'v[]',
        'label' => '选择项',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    ),
    array(
        'field' => 'type',
        'label' => '类型',
        'rules' => 'trim|required|numeric|min_length[1]'
    ),
    array(
        'field' => 'pack',
        'label' => '打包人',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
