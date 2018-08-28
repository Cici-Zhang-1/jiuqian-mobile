<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/card_model/select'] = array(
	'c_id' => 'v',
	'c_url' => 'url',
	'c_name' => 'name',
	'c_label' => 'label',
	'c_card_type' => 'card_type_v',
	'c_card_setting' => 'card_setting_v',
	'c_lazy_load' => 'lazy_load',
	'm_name' => 'menu'
);
$config['permission/card_model/select_allowed'] = array(
    'c_id' => 'v',
    'c_name' => 'name',
    'c_label' => 'label',
    'c_url' => 'url',
    'c_card_type' => 'card_type_v',
    'ct_name' => 'card_type_name',
    'c_card_setting' => 'card_setting_v',
    'c_lazy_load' => 'lazy_load',
    'cs_name' => 'card_setting_name'
);
$config['permission/card_model/select_by_mid'] = array(
	'c_id' => 'v',
	'c_name' => 'name',
    'c_label' => 'label',
	'c_url' => 'url',
	'c_card_type' => 'card_type',
	'c_card_setting' => 'card_setting',
    'c_lazy_load' => 'lazy_load',
	'c_menu_id' => 'menu_v'
);
