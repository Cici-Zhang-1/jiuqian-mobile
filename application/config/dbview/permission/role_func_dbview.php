<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_func_model/select_by_rid'] = array(
	'rf_func_id' => 'fid',
);

$config['permission/role_func_model/select_by_usergroup_v'] = array(
    'f_id' => 'v',
    'f_label' => 'label',
    'm_label' => 'menu_label'
);

$config['permission/role_func_model/select_by_role_v'] = array(
    'f_id' => 'v',
    'f_label' => 'label',
    'm_label' => 'menu_label',
    'if(A.rf_id is not null && A.rf_id > 0, 1, 0)' => 'checked'
);