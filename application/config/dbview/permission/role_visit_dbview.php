<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_visit_model/select_by_rid'] = array(
	'rv_visit_id' => 'visit_v',
);

$config['permission/role_visit_model/select_by_usergroup_v'] = array(
    'v_id' => 'v',
    'v_directory' => 'directory',
    'v_controller' => 'controller',
    'v_method' => 'method',
    'v_name' => 'name',
    'v_url' => 'url'
);

$config['permission/role_visit_model/select_by_role_v'] = array(
    'v_id' => 'v'
);