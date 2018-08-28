<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/card/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
    array(
        'field' => 'label',
        'label' => 'Label',
        'rules' => 'trim|required|min_length[1]|max_length[64]'
    ),
	array(
		'field' => 'url',
		'label' => 'Url',
		'rules' => 'trim|max_length[128]'
	),
	array(
		'field' => 'card_type_v',
		'label' => '卡片类型',
		'rules' => 'trim|required|min_length[1]|max_length[32]'
	),
	array(
		'field' => 'card_setting_v',
		'label' => '设置方式',
		'rules' => 'trim|max_length[32]'
	),
    array(
        'field' => 'lazy_load',
        'label' => '懒加载',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[1]'
    ),
    array(
        'field' => 'menu_v',
        'label' => '菜单V',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
    )
);

$config['permission/card/edit'] = array(
	array(
		'field' => 'v',
		'label' => '卡片编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
    array(
        'field' => 'name',
        'label' => '名称',
        'rules' => 'trim|required|min_length[1]|max_length[64]'
    ),
    array(
        'field' => 'label',
        'label' => 'Label',
        'rules' => 'trim|required|min_length[1]|max_length[64]'
    ),
    array(
        'field' => 'url',
        'label' => 'Url',
        'rules' => 'trim|max_length[128]'
    ),
    array(
        'field' => 'card_type_v',
        'label' => '卡片类型',
        'rules' => 'trim|required|min_length[1]|max_length[32]'
    ),
    array(
        'field' => 'lazy_load',
        'label' => '懒加载',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[1]'
    ),
    array(
        'field' => 'card_setting_v',
        'label' => '设置方式',
        'rules' => 'trim|max_length[32]'
    )
);

$config['permission/card/remove'] = array(
	array(
		'field' => 'v[]',
		'label' => '卡片编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
