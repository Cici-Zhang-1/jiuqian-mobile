<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_model/select'] = array(
                                'd_id' => 'v',
    'd_company_type' => 'company_type',
    'd_name' => 'name',
    'd_area_id' => 'area_id',
    'd_address' => 'address',
    'd_discount' => 'discount',
    'd_credit' => 'credit',
    'd_payterms' => 'payterms',
    'd_remark' => 'remark',
    'd_creator' => 'creator',
    'd_create_datetime' => 'create_datetime',
    'd_debt1' => 'debt1',
    'd_produce' => 'produce',
    'd_debt3' => 'debt3',
    'd_delivered' => 'delivered',
    'd_received' => 'received',
    'd_balance' => 'balance',
    'd_virtual_produce' => 'virtual_produce',
    'd_virtual_delivered' => 'virtual_delivered',
    'd_virtual_balance' => 'virtual_balance',
    'd_virtual_received' => 'virtual_received',
    'd_status' => 'status',
    'd_start_date' => 'start_date',
    'd_start' => 'start',
    'concat(a_province, a_city, a_county, d_address)' => 'area',
    'u_truename' => 'owner',
    'ds_label' => 'status_label',
    'd_num' => 'num',
    'd_dealer_rank' => 'dealer_rank',
    'd_down_payment' => 'down_payment',
    'dl_truename' => 'linker_name',
    'dl_mobilephone' => 'mobilephone'
    );

$config['dealer/dealer_model/is_exist'] = array(
    'd_id' => 'v',
    'd_company_type' => 'company_type',
    'd_name' => 'name',
    'd_area_id' => 'area_id',
    'd_address' => 'address',
    'd_discount' => 'discount',
    'd_credit' => 'credit',
    'd_payterms' => 'payterms',
    'd_remark' => 'remark',
    'd_creator' => 'creator',
    'd_create_datetime' => 'create_datetime',
    'd_debt1' => 'debt1',
    'd_produce' => 'produce',
    'd_debt3' => 'debt3',
    'd_delivered' => 'delivered',
    'd_received' => 'received',
    'd_balance' => 'balance',
    'd_virtual_produce' => 'virtual_produce',
    'd_virtual_delivered' => 'virtual_delivered',
    'd_virtual_balance' => 'virtual_balance',
    'd_virtual_received' => 'virtual_received',
    'd_status' => 'status',
    'd_start_date' => 'start_date',
    'd_start' => 'start',
    'd_num' => 'num',
    'd_dealer_rank' => 'dealer_rank',
    'd_down_payment' => 'down_payment',
    'concat(d_num, "_", d_name, "_" , a_province, a_city, a_county, ifnull(d_address, ""), "_", dl_truename, "_", dl_mobilephone)' => 'unique_name'
);
$config['dealer/dealer_model/is_public'] = array(
    'd_id' => 'v'
);

$config['dealer/dealer_model/select_remote'] = array(
    'd_id' => 'v',
    'concat(d_num, "_", d_name, "_" , a_province, a_city, a_county, ifnull(d_address, ""), "_", dl_truename, "_", dl_mobilephone)' => 'name'
);

$config['dealer/dealer_model/select_dealer_money'] = array(
    'd_id' => array(
        'v',
        'dealer_id'
    ),
    'concat(d_num, "_", d_name, "_" , a_province, a_city, a_county, ifnull(d_address, ""), "_", dl_truename, "_", dl_mobilephone)' => 'dealer',
    'd_balance' => 'balance',
    'd_virtual_balance' => 'virtual_balance',
    'u_truename' => 'owner'
);
