<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_trace/add'] = array(
	array(
		'field' => 'dealer_id',
		'label' => '经销商Id',
		'rules' => 'trim|required|numeric|max_length[10]'
	),
	array(
		'field' => 'trace',
		'label' => '跟踪',
		'rules' => 'trim|required|max_length[65535]'
	)
);
