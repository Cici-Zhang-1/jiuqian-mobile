<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/form_model/select'] = array(
	'A.f_id' => 'fid',
	'A.f_name' => 'name',
	'A.f_label' => 'label',
	'A.f_type' => 'type',
	'A.f_url' => 'url',
	'B.f_name' => 'func',
	'm_name' => 'menu',
);
$config['permission/form_model/select_allowed'] = array(
	'A.f_id' => 'fid',
	'A.f_name' => 'name',
	'A.f_label' => 'label',
	'A.f_type' => 'type',
	'A.f_url' => 'url',
	'B.f_name' => 'func',
);
$config['permission/form_model/select_by_fid'] = array(
	'f_id' => 'fid',
	'f_name' => 'name',
	'f_label' => 'label',
	'f_type' => 'type',
	'f_url' => 'url',
	'f_func_id' => 'func_id',
);