<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_delivery_shop_model/select'] = array(
                                'dds_id' => 'v',
    'dds_dealer_delivery_id' => 'dealer_delivery_id',
    'dds_shop_id' => 'shop_id',
    'dds_primary' => 'primary',
    'dd_dealer_id' => 'dealer_id',
    'dd_logistics' => 'logistics',
    'dd_out_method' => 'out_method',
    'dd_linker' => 'linker',
    'dd_phone' => 'phone',
    'bt_label' => 'primary_label',
    'concat(a_province, a_city, a_county, dd_address)' => 'area'
);
