<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_page_form_model/select_by_rid'] = array(
	'rpf_page_form_id' => 'v',
);

$config['permission/role_page_form_model/select_by_usergroup_v'] = array(
    'pf_id' => 'v',
    'pf_label' => 'label',
    'fp_label' => 'form_page_label',
    'm_label' => 'menu_label'
);

$config['permission/role_page_form_model/select_by_role_v'] = array(
    'pf_id' => 'v',
    'pf_label' => 'label',
    'fp_label' => 'form_page_label',
    'm_label' => 'menu_label',
    'if(A.rpf_id is not null && A.rpf_id > 0, 1, 0)' => 'checked'
);