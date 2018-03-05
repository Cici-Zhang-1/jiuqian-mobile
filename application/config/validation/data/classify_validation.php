<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/classify/add'] = array(
	array(
		'field' => 'name',
		'label' => '分类名称',
		'rules' => 'trim|required|min_length[1]|max_length[32]'
	),
	array(
		'field' => 'class',
		'label' => '等级',
		'rules' => 'trim|required|numeric|max_length[2]'
	),
	array(
		'field' => 'parent',
		'label' => '父类',
		'rules' => 'trim|required|numeric|max_length[4]'
	),
	array(
		'field' => 'flag',
		'label' => '标记',
		'rules' => 'trim|max_length[8]'
	),
	array(
		'field' => 'print_list',
		'label' => '打印清单',
		'rules' => 'trim|required|numeric|max_length[1]'
	),
	array(
		'field' => 'label',
		'label' => '打印标签',
		'rules' => 'trim|required|numeric|max_length[1]'
	),
	array(
		'field' => 'optimize',
		'label' => '进优化',
		'rules' => 'trim|required|numeric|max_length[1]'
	),
	array(
		'field' => 'plate_name',
		'label' => '板块名称',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'width_min',
		'label' => '宽最小尺寸',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'width_max',
		'label' => '宽最大尺寸',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'length_min',
		'label' => '长最小尺寸',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'length_max',
		'label' => '长最大尺寸',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'thick',
		'label' => '厚度',
		'rules' => 'trim|intval|numeric|max_length[10]'
	),
	array(
		'field' => 'edge',
		'label' => '封边',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'slot',
		'label' => '开槽',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'process',
		'label' => '流程',
		'rules' => 'trim[,]|max_length[1024]'
	),
	array(
		'field' => 'status',
		'label' => '状态',
		'rules' => 'trim|required|numeric|max_length[1]'
	)
);

$config['data/classify/edit'] = array(
	array(
		'field' => 'selected',
		'label' => '编号',
		'rules' => 'required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '分类名称',
		'rules' => 'trim|required|min_length[1]|max_length[32]'
	),
	array(
		'field' => 'class',
		'label' => '等级',
		'rules' => 'trim|required|numeric|max_length[2]'
	),
	array(
		'field' => 'parent',
		'label' => '父类',
		'rules' => 'trim|required|numeric|max_length[4]'
	),
	array(
		'field' => 'flag',
		'label' => '标记',
		'rules' => 'trim|max_length[8]'
	),
	array(
		'field' => 'print_list',
		'label' => '打印清单',
		'rules' => 'trim|required|numeric|max_length[1]'
	),
	array(
		'field' => 'label',
		'label' => '打印标签',
		'rules' => 'trim|required|numeric|max_length[1]'
	),
	array(
		'field' => 'optimize',
		'label' => '进优化',
		'rules' => 'trim|required|numeric|max_length[1]'
	),
	array(
		'field' => 'plate_name',
		'label' => '板块名称',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'width_min',
		'label' => '宽最小尺寸',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'width_max',
		'label' => '宽最大尺寸',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'length_min',
		'label' => '长最小尺寸',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'length_max',
		'label' => '长最大尺寸',
		'rules' => 'trim|floatval|decimal|max_length[10]'
	),
	array(
		'field' => 'thick',
		'label' => '厚度',
		'rules' => 'trim|intval|numeric|max_length[10]'
	),
	array(
		'field' => 'edge',
		'label' => '封边',
		'rules' => 'trim|max_length[32]'
	),
	array(
		'field' => 'slot',
		'label' => '开槽',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'remark',
		'label' => '备注',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'process',
		'label' => '流程',
		'rules' => 'trim[,]|max_length[1024]'
	),
	array(
		'field' => 'status',
		'label' => '状态',
		'rules' => 'trim|required|numeric|max_length[1]'
	)
);

$config['data/classify/remove'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);

$config['data/classify/act'] = array(
	array(
		'field' => 'selected[]',
		'label' => '选择项',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
