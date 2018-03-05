<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/electric_saw/edit'] = array(
	array(
		'field' => 'selected[]',
		'label' => '确认下料批次号',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
	)
);
