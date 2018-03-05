<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['finance/finance_back/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '进账编号',
		'rules' => 'trim|required|max_length[1024]'
	),
	array(
		'field' => 'faid',
		'label' => '账号',
		'rules' => 'trim|required|numeric|max_length[4]'
	)
);
