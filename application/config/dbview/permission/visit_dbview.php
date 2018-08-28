<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/visit_model/select'] = array(
	'v_id' => 'v',
	'v_directory' => 'directory',
	'v_controller' => 'controller',
	'v_method' => 'method',
	'v_name' => 'name',
	'v_url' => 'url',
);
$config['permission/visit_model/select_allowed_by_ugid'] = array(
	'v_id' => 'v',
	'v_name' => 'name',
	'v_url' => 'url',
);
$config['permission/visit_model/select_is_allowed_operation'] = array(
	'v_id' => 'visit_v',
);