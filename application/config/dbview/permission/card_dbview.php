<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/card_model/select'] = array(
	'c_id' => 'cid',
	'c_url' => 'url',
	'c_name' => 'name',
	'm_name' => 'menu',
);
$config['permission/card_model/select_allowed'] = array(
    'c_id' => 'cid',
    'c_name' => 'name',
    'c_url' => 'url',
    'c_card_type' => 'card_type',
    'c_card_setting' => 'card_setting'
);
$config['permission/card_model/select_by_mid'] = array(
	'c_id' => 'cid',
	'c_name' => 'name',
	'c_url' => 'url',
	'c_card_type' => 'card_type',
	'c_card_setting' => 'card_setting',
	'c_menu_id' => 'mid',
);
