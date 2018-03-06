<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_linker_model/insert'] = array(
	'name' => 'dl_name',
	'dealer_id' => 'dl_dealer_id',
	'mobilephone' => 'dl_mobilephone',
	'telephone' => 'dl_telephone',
	'email' => 'dl_email',
	'qq' => 'dl_qq',
	'fax' => 'dl_fax',
	'doid' => 'dl_type',
	'creator' => 'dl_creator_id',
	'create_datetime' => 'dl_create_datetime',
);
$config['dealer/dealer_linker_model/update'] = array(
	'name' => 'dl_name',
	'dealer_id' => 'dl_dealer_id',
	'mobilephone' => 'dl_mobilephone',
	'telephone' => 'dl_telephone',
	'email' => 'dl_email',
	'qq' => 'dl_qq',
	'fax' => 'dl_fax',
	'doid' => 'dl_type',
	'primary' => 'dl_primary',
);