<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/menu/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'label',
		'label' => 'Label',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'parent',
		'label' => '父级',
		'rules' => 'trim|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'url',
		'label' => 'URL',
		'rules' => 'trim|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'displayorder',
		'label' => '显示顺序',
		'rules' => 'trim|numeric|min_length[0]|max_length[4]'
	),
	array(
		'field' => 'img',
		'label' => '图像',
		'rules' => 'trim|min_length[0]|max_length[128]'
	),
	array(
		'field' => 'page_type_v',
		'label' => '类型',
		'rules' => 'trim|min_length[0]|max_length[32]'
	),
	array(
		'field' => 'mobile_v',
		'label' => '移动端显示',
		'rules' => 'trim|numeric|min_length[0]|max_length[1]'
	),
    array(
        'field' => 'invisible_v',
        'label' => '菜单内隐藏',
        'rules' => 'trim|numeric|min_length[0]|max_length[1]'
    ),
    array(
        'field' => 'home',
        'label' => '首页',
        'rules' => 'trim|numeric|min_length[0]|max_length[1]'
    )
);

$config['permission/menu/edit'] = array(
	array(
		'field' => 'v',
		'label' => '菜单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'label',
		'label' => 'Label',
		'rules' => 'trim|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'parent',
		'label' => '父级',
		'rules' => 'trim|numeric|min_length[1]|max_length[4]'
	),
	array(
		'field' => 'url',
		'label' => 'URI',
		'rules' => 'trim|min_length[1]|max_length[128]'
	),
	array(
		'field' => 'displayorder',
		'label' => '显示顺序',
		'rules' => 'trim|numeric|min_length[0]|max_length[4]'
	),
	array(
		'field' => 'img',
		'label' => '图像',
		'rules' => 'trim|min_length[0]|max_length[128]|gh_str_replace'
	),
    array(
        'field' => 'page_type_v',
        'label' => '类型',
        'rules' => 'trim|min_length[0]|max_length[32]'
    ),
    array(
        'field' => 'mobile_v',
        'label' => '移动端显示',
        'rules' => 'trim|numeric|min_length[0]|max_length[1]'
    ),
    array(
        'field' => 'invisible_v',
        'label' => '菜单内隐藏',
        'rules' => 'trim|numeric|min_length[0]|max_length[1]'
    ),
    array(
        'field' => 'home',
        'label' => '首页',
        'rules' => 'trim|numeric|min_length[0]|max_length[1]'
    )
);

$config['permission/menu/remove'] = array(
	array(
		'field' => 'v[]',
		'label' => '菜单编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
