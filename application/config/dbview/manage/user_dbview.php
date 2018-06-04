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
    'U.u_create_datetime' => 'create_datetime'
);
$config['manage/user_model/select_self'] = array(
	'u_id' => 'uid',
	'u_mobilephone' => 'mobilephone',
	'u_name' => 'username',
	'u_truename' => 'truename',
	'u_password' => 'password',
);
$config['manage/user_model/check_username'] = array(
	'U.u_id' => 'uid',
	'U.u_name' => 'username',
	'U.u_password' => 'password',
	'U.u_salt' => 'salt',
	'U.u_truename' => 'truename',
	'U.u_mobilephone' => 'mobilephone',
	'U.u_usergroup_id' => 'ugid',
	'UG.u_name' => 'usergroup',
);
$config['manage/user_model/is_user'] = array(
	'U.u_id' => 'uid',
	'U.u_name' => 'username',
	'U.u_truename' => 'truename',
	'U.u_mobilephone' => 'mobilephone',
	'U.u_usergroup_id' => 'ugid',
	'UG.u_name' => 'usergroup',
);
$config['manage/user_model/select'] = array(
	'u_id' => 'uid',
	'u_name' => 'name',
	'u_truename' => 'truename',
);
$config['manage/user_model/select_by_usergroup'] = array(
	'U.u_id' => 'uid',
	'U.u_name' => 'username',
	'U.u_truename' => 'truename',
	'U.u_mobilephone' => 'mobilephone',
	'U.u_usergroup_id' => 'ugid',
	'U.u_create_datetime' => 'create_datetime',
	'UG.u_name' => 'usergroup',
	'UG.u_class' => 'class',
	'UG.u_parent' => 'parent',
	'C.u_truename' => 'creator',
);

$config['manage/user_model/signed_in'] = array(
    'u_id' => 'uid',
    'u_name' => 'name',
    'u_truename' => 'truename'
);
