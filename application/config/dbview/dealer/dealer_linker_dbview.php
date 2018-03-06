<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_linker_model/select'] = array(
	'dl_id' => 'dlid',
	'dl_name' => 'name',
	'dl_mobilephone' => 'mobilephone',
	'dl_telephone' => 'telephone',
	'dl_email' => 'email',
	'dl_qq' => 'qq',
	'dl_fax' => 'fax',
	'do_id' => 'doid',
	'do_name' => 'organization',
	'dl_primary' => 'primarys',
	'if(dl_primary,'<i class="fa fa-check"></i>', "")' => 'icon',
);