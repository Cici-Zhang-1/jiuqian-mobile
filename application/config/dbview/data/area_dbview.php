<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/area_model/select'] = array(
	'a_id' => 'aid',
	'a_province' => 'province',
	'a_city' => 'city',
	'a_county' => 'county',
	'concat(ifnull(a_province, ""), ifnull(a_city, ""), ifnull(a_county,""))' => 'area',
);