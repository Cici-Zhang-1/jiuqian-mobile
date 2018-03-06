<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['manage/user/read'] = array(
	'u_id' => 'uid',
	'u_name' => 'name',
	'u_truename' => 'truename',
	'u_mobilephone' => 'mobilephone',
	'creator' => 'creator',
	'u_create_datetime' => 'create_datetime',
	'r_id' => 'rid',
	'r_des' => 'role',
	'r_class' => 'class',
	'r_parent' => 'parent',
);
$config['manage/workflow/read'] = array(
	'w_id' => 'wid',
	'w_name' => 'name',
	'w_code' => 'code',
	'w_parent' => 'parent',
	'w_class' => 'class',
	'w_remark' => 'remark',
);
$config['manage/organization/read'] = array(
	'o_id' => 'oid',
	'o_name' => 'name',
	'o_class' => 'class',
	'o_parent' => 'parent',
);