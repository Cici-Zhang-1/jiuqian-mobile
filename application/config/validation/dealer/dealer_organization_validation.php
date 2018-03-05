<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_organization/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);

$config['dealer/dealer_organization/edit'] = array(
	array(
		'field' => 'selected[]',
		'label' => '组织结构编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	)
);
