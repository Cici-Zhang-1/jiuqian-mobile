<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/logistics_model/select'] = array(
	'l_id' => 'lid',
	'l_name' => 'name',
	'a_id' => 'aid',
	'l_address' => 'address',
	'l_phone' => 'phone',
	'l_vip' => 'vip',
	'concat(ifnull(a_province, ""), ifnull(a_city, ""), ifnull(a_county, ""))' => 'area',
);