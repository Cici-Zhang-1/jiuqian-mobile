<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_element_model/select_by_rid'] = array(
	're_element_id' => 'element_v',
);

$config['permission/role_element_model/select_by_usergroup_v'] = array(
    'e_id' => 'v',
    'e_label' => 'label',
    'c_label' => 'card_label',
    'm_label' => 'menu_label'
);

$config['permission/role_element_model/select_by_role_v'] = array(
    'e_id' => 'v',
    'e_label' => 'label',
    'c_label' => 'card_label',
    'm_label' => 'menu_label',
    'if(A.re_id is not null && A.re_id > 0, 1, 0)' => 'checked'
);