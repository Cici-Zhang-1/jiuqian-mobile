<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_category/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[32]'
	)
);

$config['dealer/dealer_category/edit'] = array(
	array(
		'field' => 'selected[]',
		'label' => '经销商类别编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[32]'
	)
);
