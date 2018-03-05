<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['drawing/drawing/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '图纸编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
