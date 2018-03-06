<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['manage/myself/save'] = array(
	'name' => 'u_name',
	'truename' => 'u_truename',
	'mobilephone' => 'u_mobilephone',
	'password' => 'u_password',
);
$config['manage/organization'] = array(
	'name' => 'o_name',
	'class' => 'o_class',
	'parent' => 'o_parent',
);
$config['manage/user'] = array(
	'name' => 'u_name',
	'truename' => 'u_truename',
	'mobilephone' => 'u_mobilephone',
	'password' => 'u_password',
	'rid' => 'u_role_id',
	'creator' => 'u_creator',
	'create_datetime' => 'u_create_datetime',
);
$config['manage/workflow'] = array(
	'name' => 'w_name',
	'code' => 'w_code',
	'parent' => 'w_parent',
	'class' => 'w_class',
	'remark' => 'w_remark',
);