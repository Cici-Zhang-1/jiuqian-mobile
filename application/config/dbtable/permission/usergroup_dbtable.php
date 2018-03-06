<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/usergroup_model/insert'] = array(
	'name' => 'u_name',
	'parent' => 'u_parent',
	'class' => 'u_class',
	'creator' => 'u_creator',
	'create_datetime' => 'u_create_datetime',
);
$config['permission/usergroup_model/update'] = array(
	'name' => 'u_name',
	'parent' => 'u_parent',
	'class' => 'u_class',
);