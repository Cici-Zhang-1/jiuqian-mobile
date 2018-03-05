<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/cargo_no/edit'] = array(
	array(
		'field' => 'cargo_no[]',
		'label' => '货号',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);
