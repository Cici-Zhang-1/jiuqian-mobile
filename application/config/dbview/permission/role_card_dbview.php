<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_card_model/select_by_rid'] = array(
	'rc_card_id' => 'card_v',
);

$config['permission/role_card_model/select_by_usergroup_v'] = array(
    'c_id' => 'v',
    'c_label' => 'label',
    'm_label' => 'menu_label'
);

$config['permission/role_card_model/select_by_role_v'] = array(
    'c_id' => 'v',
    'c_label' => 'label',
    'm_label' => 'menu_label',
    'if(A.rc_id is not null && A.rc_id > 0, 1, 0)' => 'checked'
);