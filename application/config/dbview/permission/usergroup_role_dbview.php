<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/usergroup_role_model/select'] = array(
	'ur_id' => 'id',
	'ur_usergroup_id' => 'usergroup_id',
	'u_name' => 'usergroup',
	'ur_role_id' => 'role_id',
	'r_name' => 'role',
);
$config['permission/usergroup_role_model/select_by_child'] = array(
	'r_id' => 'rid',
	'r_name' => 'name',
);
$config['permission/usergroup_role_model/select_by_uid'] = array(
	'ur_role_id' => 'rid',
);