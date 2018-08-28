<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['manage/user/add'] = array(
	array(
		'field' => 'name',
		'label' => '用户名',
		'rules' => 'trim|required|min_length[1]|max_length[64]|is_unique[user.u_name]'
	),
	array(
		'field' => 'truename',
		'label' => '真实姓名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'password',
		'label' => '密码',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'mobilephone',
		'label' => '移动电话',
		'rules' => 'trim|numeric|min_length[0]|max_length[16]'
	),
	array(
		'field' => 'usergroup_v',
		'label' => '用户组',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
    array(
        'field' => 'group_no',
        'label' => '组内编号',
        'rules' => 'trim|numeric|min_length[1]|max_length[2]'
    )
);

$config['manage/user/edit'] = array(
	array(
		'field' => 'v',
		'label' => '用户编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	),
	array(
		'field' => 'name',
		'label' => '用户名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'truename',
		'label' => '真实姓名',
		'rules' => 'trim|required|min_length[1]|max_length[64]'
	),
	array(
		'field' => 'password',
		'label' => '密码',
		'rules' => 'trim|max_length[16]'
	),
	array(
		'field' => 'mobilephone',
		'label' => '移动电话',
		'rules' => 'trim|numeric|min_length[0]|max_length[16]'
	),
	array(
		'field' => 'usergroup_v',
		'label' => '用户组',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
	),
    array(
        'field' => 'group_no',
        'label' => '组内编号',
        'rules' => 'trim|numeric|min_length[1]|max_length[2]'
    )
);
$config['manage/user/start'] = array(
    array(
        'field' => 'v[]',
        'label' => '用户编号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);

$config['manage/user/stop'] = array(
    array(
        'field' => 'v[]',
        'label' => '用户编号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);

$config['manage/user/offtime'] = array(
    array(
        'field' => 'v[]',
        'label' => '用户编号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
$config['manage/user/remove'] = array(
	array(
		'field' => 'v[]',
		'label' => '用户编号',
		'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
	)
);
