<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/shop_model/select'] = array(
                                's_id' => 'v',
    's_name' => 'name',
    's_dealer_id' => 'dealer_id',
    's_area_id' => 'area_id',
    's_address' => 'address',
    's_produce' => 'produce',
    's_delivered' => 'delivered',
    's_received' => 'received',
    's_balance' => 'balance',
    's_remark' => 'remark',
    's_creator' => 'creator',
    's_create_datetime' => 'create_datetime',
    'concat(a_province, a_city, a_county, s_address)' => 'area',
    's_num' => 'num'
);
$config['dealer/shop_model/select_my_shop'] = array(
    's_id' => 'shop_id',
    's_dealer_id' => 'dealer_id',
    'concat(d_num, "-", s_num, "_", d_name, "_", if(d_name = s_name, "", s_name), "_" , a_province, a_city, a_county, ifnull(s_address, ""), "_", dl_truename, "_", dl_mobilephone)' => 'name'
);
$config['dealer/shop_model/select_primary_info'] = array(
    'dl_truename' => 'primary_linker',
    'dl_mobilephone' => 'primary_phone',
    'dd_logistics' => 'logistics',
    'dd_out_method' => 'out_method',
    'dd_linker' => 'delivery_linker',
    'dd_phone' => 'delivery_phone',
    'concat(a_province, a_city, a_county, dd_address)' => 'delivery_area',
    'dd_address' => 'delivery_address',
    'd_down_payment' => 'down_payment'
);
