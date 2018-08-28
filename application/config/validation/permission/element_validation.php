<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/element/add'] = array(
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
    array(
        'field' => 'card_v',
        'label' => '卡片V',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
    ),
	array(
		'field' => 'label',
		'label' => '标签',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
    array(
        'field' => 'dv',
        'label' => '默认值',
        'rules' => 'trim|min_length[1]|max_length[128]'
    ),
	array(
		'field' => 'classes',
		'label' => '样式',
		'rules' => 'trim|max_length[64]'
	),
	array(
		'field' => 'displayorder',
		'label' => '显示顺序',
		'rules' => 'trim|numeric|max_length[4]'
	),
	array(
		'field' => 'checked_v',
		'label' => '默认',
		'rules' => 'trim|numeric|max_length[1]'
	),
    array(
        'field' => 'url',
        'label' => 'URL',
        'rules' => 'trim|max_length[256]'
    ),
    array(
        'field' => 'query',
        'label' => 'URL参数',
        'rules' => 'trim|max_length[128]'
    )
);

$config['permission/element/edit'] = array(
	array(
		'field' => 'v',
		'label' => '卡片编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
    array(
        'field' => 'card_id',
        'label' => '卡片V',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
    ),
	array(
		'field' => 'name',
		'label' => '名称',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'label',
		'label' => '标签',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'classes',
		'label' => '样式',
		'rules' => 'trim|max_length[64]'
	),
    array(
        'field' => 'dv',
        'label' => '默认值',
        'rules' => 'trim|min_length[1]|max_length[128]'
    ),
	array(
		'field' => 'displayorder',
		'label' => '显示顺序',
		'rules' => 'trim|numeric|max_length[4]'
	),
	array(
		'field' => 'checked_v',
		'label' => '默认',
		'rules' => 'trim|numeric|max_length[1]'
	),
    array(
        'field' => 'url',
        'label' => 'URL',
        'rules' => 'trim|max_length[256]'
    ),
    array(
        'field' => 'query',
        'label' => 'URL参数',
        'rules' => 'trim|max_length[128]'
    )
);

$config['permission/element/remove'] = array(
	array(
		'field' => 'v[]',
		'label' => '卡片编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	)
);
