<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_owner/add'] = array(
	array(
		'field' => 'uid[]',
		'label' => '属主',
		'rules' => 'trim|required|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'dealer_id',
		'label' => '客户',
		'rules' => 'trim|required|numeric|max_length[10]'
	),
	array(
		'field' => 'primary',
		'label' => '首要',
		'rules' => 'trim|required|numeric|max_length[1]'
	)
);

$config['dealer/dealer_owner/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '需要认领的经销商编号',
		'rules' => 'trim|required|min_length[1]|max_length[1024]'
	),
	array(
		'field' => 'user[]',
		'label' => '属主',
		'rules' => 'trim|numeric|max_length[10]'
	)
);

$config['dealer/dealer_owner/primary'] = array(
	array(
		'field' => 'selected',
		'label' => '属主',
		'rules' => 'trim|required|min_length[1]|max_length[10]'
	)
);

$config['dealer/dealer_owner/general'] = array(
	array(
		'field' => 'selected[]',
		'label' => '属主',
		'rules' => 'trim|required|min_length[1]|max_length[10]'
	)
);

$config['dealer/dealer_owner/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '属主',
		'rules' => 'trim|required|min_length[1]|max_length[10]'
	)
);
