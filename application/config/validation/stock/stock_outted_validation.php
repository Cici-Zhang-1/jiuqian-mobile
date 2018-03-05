<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['stock/stock_outted/redelivery'] = array(
	array(
		'field' => 'selected[]',
		'label' => '重新发货的货号',
		'rules' => 'required|numeric|min_length[1]|max_length[10]'
	)
);
