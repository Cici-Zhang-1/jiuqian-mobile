<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/usergroup_role/edit'] = array(
    array(
        'field' => 'usergroup_v',
        'label' => '用户组',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
    ),
    array(
        'field' => 'v[]',
        'label' => '角色权限',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[4]'
    )
);
