<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_claim/edit'] = array(
	array(
		'field' => 'selected[]',
		'label' => '需要认领的经销商编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
