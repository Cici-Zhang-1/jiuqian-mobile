<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_delivery_model/select'] = array(
	'dd_id' => 'ddid',
	'a_id' => 'daid',
	'concat(ifnull(a_province,""), ifnull(a_city, ""), ifnull(a_county, ""))' => 'area',
	'dd_address' => 'delivery_address',
	'l_id' => 'lid',
	'l_name' => 'logistics',
	'om_id' => 'omid',
	'om_name' => 'out_method',
	'dd_name' => 'delivery_linker',
	'dd_phone' => 'delivery_phone',
	'dd_default' => 'defaults',
	'if(dd_default,'<i class="fa fa-check"></i>', "")' => 'icon',
);