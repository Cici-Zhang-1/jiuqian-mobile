<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['permission/func_model/select'] = array(
	'f_id' => 'fid',
	'f_name' => 'name',
	'f_label' => 'label',
	'm_name' => 'menu',
);
$config['permission/func_model/select_by_mid'] = array(
	'f_id' => 'fid',
	'f_name' => 'name',
	'f_label' => 'label',
	'f_url' => 'url',
	'f_displayorder' => 'displayorder',
	'f_img' => 'img',
	'f_group_no' => 'group_no',
	'f_toggle' => 'toggle',
	'f_target' => 'target',
	'f_multiple' => 'multiple',
	'f_source' => 'source',
    'f_modal_type' => 'modal_type',
	'f_tag' => 'tag',
	'f_menu_id' => 'mid',
);
$config['permission/func_model/select_allowed'] = array(
	'f_id' => 'fid',
	'f_name' => 'name',
	'f_label' => 'label',
	'f_url' => 'url',
    'f_img' => 'img',
    'f_group_no' => 'group_no',
    'f_toggle' => 'toggle_v',
    'TOGGLE.tt_name' => 'toggle_name',
    'f_target' => 'target',
    'f_tag' => 'tag_v',
    'TAG.tt_name' => 'tag_name',
    'f_source' => 'source',
    'f_modal_type' => 'modal_type_v',
    'mt_name' => 'modal_type_name',
    'f_multiple' => 'multiple_v',
    'bt_name' => 'multiple_name'
);
$config['permission/func_model/select_is_allowed_operation'] = array(
	'f_id' => 'fid',
);
