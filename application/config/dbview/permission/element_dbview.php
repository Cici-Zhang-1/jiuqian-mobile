<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/element_model/select'] = array(
	'e_id' => 'v',
	'e_name' => 'name',
	'e_label' => 'label',
	'e_classes' => 'classes',
	'e_dv' => 'dv',
	'e_displayorder' => 'displayorder',
	'e_checked' => 'checked_v',
	'e_card_id' => 'card_id',
	'c_name' => 'card_label',
	'm_name' => 'menu_label',
    'e_url' => 'url',
    'e_query' => 'query'
);
$config['permission/element_model/select_allowed'] = array(
    'e_id' => 'v',
    'e_name' => 'name',
    'e_label' => 'label',
    'e_classes' => 'classes',
    'e_dv' => 'dv',
    'e_checked' => 'checked_v',
    'bt_name' => 'checked_name',
    'e_displayorder' => 'displayorder',
    'e_card_id' => 'card_id',
    'c_name' => 'card',
    'e_url' => 'url',
    'e_query' => 'query',
    'c_menu_id' => 'menu_id',
);
$config['permission/element_model/select_by_card_url'] = array(
	'e_id' => 'v',
	'e_name' => 'name',
	'e_checked' => 'checked',
	'c_name' => 'card',
    'e_url' => 'url',
    'e_query' => 'query'
);
$config['permission/element_model/select_by_cid'] = array(
	'e_id' => 'v',
	'e_name' => 'name',
	'e_label' => 'label',
	'e_checked' => 'checked',
	'e_classes' => 'classes',
	'e_displayorder' => 'displayorder',
	'e_card_id' => 'card_v',
    'e_url' => 'url',
    'e_query' => 'query'
);
