<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_owner_model/select'] = array(
	'd_id' => 'did',
	'd_des' => 'dealer',
	'p_name' => 'payterms',
	'concat(ifnull(d.a_province,""), ifnull(d.a_city, ""), ifnull(d.a_county, ""), ifnull(d_address,""))' => 'dealer_address',
	'A.dl_name' => 'dealer_linker',
	'if(A.dl_mobilephone = "", A.dl_telephone, A.dl_mobilephone)' => 'dealer_phone',
	'B.dl_name' => 'checker',
	'if(B.dl_mobilephone = "", B.dl_telephone, B.dl_mobilephone)' => 'checker_phone',
	'C.dl_name' => 'payer',
	'if(C.dl_mobilephone = "", C.dl_telephone, C.dl_mobilephone)' => 'payer_phone',
	'concat(ifnull(d.a_province,""), ifnull(d.a_city, ""), ifnull(dd.a_county, ""))' => 'delivery_area',
	'dd_address' => 'delivery_address',
	'dd_name' => 'delivery_linker',
	'dd_phone' => 'delivery_phone',
	'l_name' => 'logistics',
	'om_name' => 'out_method',
);
$config['dealer/dealer_owner_model/select_owner'] = array(
	'do_id' => 'doid',
	'u_truename' => 'truename',
	'do_primary' => 'primarys',
	'if(do_primary = 1, "<i class=\"fa fa-check\"></i>", "<i class=\"fa fa-times\"></i>")' => 'icon',
);