<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['position/position_order_product/add'] = array(
	array(
		'field' => 'selected',
		'label' => '库位号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'opid',
		'label' => '订单产品编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'new_order_product_num',
		'label' => '新入库订单编号',
		'rules' => 'trim|required|max_length[128]'
	),
	array(
		'field' => 'count',
		'label' => '入库件数',
		'rules' => 'trim|required|numeric|greater_than[0]|max_length[128]'
	),
	array(
		'field' => 'status',
		'label' => '库位状态',
		'rules' => 'trim|required|numeric|greater_than[0]|max_length[1]'
	)
);

$config['position/position_order_product/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '库位号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'count',
		'label' => '入库件数',
		'rules' => 'trim|required|numeric|greater_than[0]|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'status',
		'label' => '订单在库位状态',
		'rules' => 'trim|required|numeric|max_length[1]'
	)
);

$config['position/position_order_product/edit_out'] = array(
	array(
		'field' => 'selected[]',
		'label' => '库位号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);

$config['position/position_order_product/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '库位号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
