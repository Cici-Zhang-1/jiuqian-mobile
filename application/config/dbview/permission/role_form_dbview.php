<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/role_form_model/select_by_rid'] = array(
	'rf_form_id' => 'form_v',
);

$config['permission/role_form_model/select_by_usergroup_v'] = array(
    'FORM.f_id' => 'v',
    'FORM.f_label' => 'label',
    'FUNC.f_label' => 'func_label',
    'm_label' => 'menu_label'
);

$config['permission/role_form_model/select_by_role_v'] = array(
    'FORM.f_id' => 'v',
    'FORM.f_label' => 'label',
    'FUNC.f_label' => 'func_label',
    'm_label' => 'menu_label',
    'if(A.rf_id is not null && A.rf_id > 0, 1, 0)' => 'checked'
);