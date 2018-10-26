<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['manage/user_model/insert'] = array(
	'name' => 'u_name',
	'truename' => 'u_truename',
	'mobilephone' => 'u_mobilephone',
	'password' => 'u_password',
	'usergroup_v' => 'u_usergroup_id',
	'creator' => 'u_creator',
	'create_datetime' => 'u_create_datetime',
    'group_no' => 'u_group_no'
);
$config['manage/user_model/update'] = array(
	'name' => 'u_name',
	'truename' => 'u_truename',
	'mobilephone' => 'u_mobilephone',
	'password' => 'u_password',
	'usergroup_v' => 'u_usergroup_id',
    'status' => 'u_status',
    'group_no' => 'u_group_no',
    'user_id' => 'u_user_id'
);
$config['manage/user_model/update_batch'] = array(
    'v' => 'u_id',
    'name' => 'u_name',
    'truename' => 'u_truename',
    'mobilephone' => 'u_mobilephone',
    'password' => 'u_password',
    'usergroup_v' => 'u_usergroup_id',
    'status' => 'u_status',
    'group_no' => 'u_group_no',
    'user_id' => 'u_user_id'
);