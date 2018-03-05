<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/scan/edit'] = array(
	array(
		'field' => 'selected[]',
		'label' => '已扫描项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
