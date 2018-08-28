<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_delivery_model/select'] = array(
                                'dd_id' => 'v',                                        'dd_dealer_id' => 'dealer_id',                                        'dd_area_id' => 'area_id',                                        'dd_address' => 'address',                                        'dd_logistics' => 'logistics',                                        'dd_out_method' => 'out_method',                                        'dd_linker' => 'linker',                                        'dd_phone' => 'phone',
    'dd_creator' => 'creator',
    'dd_create_datetime' => 'create_datetime',
    'dd_primary' => 'primary',
    'bt_label' => 'primary_label',
    'concat(a_province, a_city, a_county, dd_address)' => 'area',
    'concat(dd_linker, a_province, a_city, a_county, dd_address)' => 'name'
);
