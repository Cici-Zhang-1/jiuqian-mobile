<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/usergroup_role_model/insert'] = array(
	'uid' => 'ur_usergroup_id',
	'rid' => 'ur_role_id',
);
$config['permission/usergroup_role_model/insert_batch'] = array(
	'uid' => 'ur_usergroup_id',
	'rid' => 'ur_role_id',
);
$config['permission/usergroup_role_model/update'] = array(
	'usergroup_id' => 'ur_usergroup_id',
	'role_id' => 'ur_role_id',
);