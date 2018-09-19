<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['data/area_model/select'] = array(
    'a_id' => 'v',
    'concat(a_province, a_city, a_county)' => 'name',
    'a_province' => 'province',
    'a_city' => 'city',
    'a_county' => 'county'
);
