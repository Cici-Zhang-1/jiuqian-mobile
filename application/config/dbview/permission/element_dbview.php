<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/element_model/select'] = array(
	'e_id' => 'eid',
	'e_name' => 'name',
	'e_label' => 'label',
	'e_checked' => 'checked',
	'c_name' => 'card',
	'm_name' => 'menu',
);
$config['permission/element_model/select_allowed'] = array(
    'e_id' => 'eid',
    'e_name' => 'name',
    'e_label' => 'label',
    'e_classes' => 'classes',
    'e_dv' => 'dv',
    'e_checked' => 'checked_v',
    'bt_name' => 'checked_name',
    'e_displayorder' => 'displayorder',
    'c_name' => 'card'
);
$config['permission/element_model/select_by_card_url'] = array(
	'e_id' => 'eid',
	'e_name' => 'name',
	'e_checked' => 'checked',
	'c_name' => 'card',
);
$config['permission/element_model/select_by_cid'] = array(
	'e_id' => 'eid',
	'e_name' => 'name',
	'e_label' => 'label',
	'e_checked' => 'checked',
	'e_classes' => 'classes',
	'e_displayorder' => 'displayorder',
	'e_card_id' => 'cid',
);
