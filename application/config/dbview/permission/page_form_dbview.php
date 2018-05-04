<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/page_form_model/select'] = array(
	'pf_id' => 'pfid',
	'pf_name' => 'name',
	'pf_label' => 'label',
	'pf_url' => 'url',
	'pf_form_type' => 'form_type',
	'm_name' => 'menu',
);
$config['permission/page_form_model/select_allowed'] = array(
	'pf_id' => 'pfid',
	'pf_name' => 'name',
	'pf_label' => 'label',
	'pf_url' => 'url',
	'pf_form_type' => 'form_type_v',
    'pf_readonly' => 'readonly_v',
    'READONLY.bt_name' => 'readonly_name',
    'pf_required' => 'required_v',
    'REQUIRED.bt_name' => 'required_name',
    'pf_multiple' => 'multiple_v',
    'MULTIPLE.bt_name' => 'multiple_name'
);
$config['permission/page_form_model/select_by_mid'] = array(
	'pf_id' => 'pfid',
	'pf_name' => 'name',
	'pf_label' => 'label',
	'pf_url' => 'url',
	'pf_form_type' => 'form_type',
	'pf_menu_id' => 'mid',
);
