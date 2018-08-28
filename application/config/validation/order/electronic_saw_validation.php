<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/electronic_saw/edit'] = array(
	array(
		'field' => 'v[]',
		'label' => '确认下料批次号',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
	)
);
