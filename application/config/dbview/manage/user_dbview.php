<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['manage/user_model/select'] = array(
    'U.u_id' => 'v',
    'U.u_name' => 'name',
    'U.u_mobilephone' => 'mobilephone',
    'U.u_truename' => 'truename',
    'UG.u_id' => 'usergroup_v',
    'UG.u_name' => 'usergroup_name',
    'UG.u_class' => 'usergroup_class',
    'UG.u_parent' => 'usergroup_parent',
    'UC.u_name' => 'creator',
    'U.u_create_datetime' => 'create_datetime',
    'U.u_status' => 'status',
    'U.u_group_no' => 'group_no'
);
$config['manage/user_model/select_self'] = array(
	'u_id' => 'v',
	'u_mobilephone' => 'mobilephone',
	'u_name' => 'name',
	'u_truename' => 'truename',
    'us_label' => 'status_label',
    'u_group_no' => 'group_no'
);
$config['manage/user_model/select_usergroup_v'] = array(
    'u_id' => 'v',
    'u_usergroup_id' => 'usergroup_v'
);
$config['manage/user_model/check_name'] = array(
	'U.u_id' => 'uid',
	'U.u_name' => 'name',
	'U.u_password' => 'password',
	'U.u_salt' => 'salt',
	'U.u_truename' => 'truename',
	'U.u_mobilephone' => 'mobilephone',
	'U.u_usergroup_id' => 'ugid',
	'U.u_status' => 'status',
	'UG.u_name' => 'usergroup',
    'U.u_group_no' => 'group_no'
);
$config['manage/user_model/is_user'] = array(
	'U.u_id' => 'uid',
	'U.u_name' => 'name',
	'U.u_truename' => 'truename',
	'U.u_mobilephone' => 'mobilephone',
	'U.u_usergroup_id' => 'ugid',
    'U.u_status' => 'status',
	'UG.u_name' => 'usergroup',
    'U.u_group_no' => 'group_no'
);
$config['manage/user_model/select_by_usergroup'] = array(
	'U.u_id' => 'uid',
	'U.u_name' => 'name',
	'U.u_truename' => 'truename',
	'U.u_mobilephone' => 'mobilephone',
	'U.u_usergroup_id' => 'ugid',
	'U.u_create_datetime' => 'create_datetime',
    'U.u_status' => 'status',
	'UG.u_name' => 'usergroup',
	'UG.u_class' => 'class',
	'UG.u_parent' => 'parent',
	'C.u_truename' => 'creator',
    'U.u_group_no' => 'group_no'
);
$config['manage/user_model/select_usergroup'] = array(
    'u_usergroup_id' => 'usergroup_v'
);

$config['manage/user_model/signed_in'] = array(
    'u_id' => 'uid',
    'u_name' => 'name',
    'u_truename' => 'truename',
    'u_status' => 'status'
);

$config['manage/user_model/is_exist'] = array(
    'U.u_id' => 'v',
    'U.u_name' => 'name',
    'U.u_truename' => 'truename',
    'U.u_status' => 'status',
    'U.u_group_no' => 'group_no',
    'UG.u_amount' => 'usergroup_amount',
);

$config['manage/user_model/work_status'] = array(
    'U.u_id' => 'v',
    'U.u_status' => 'status',
    'UG.u_name' => 'usergroup',
    'U.u_group_no' => 'group_no'
);
