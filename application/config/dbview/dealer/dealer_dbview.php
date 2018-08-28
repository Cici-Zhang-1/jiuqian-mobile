<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_model/select'] = array(
                                'd_id' => 'v',
    'd_company_type' => 'company_type',
    'd_name' => 'name',
    'd_area_id' => 'area_id',
    'd_address' => 'address',                                        'd_discount' => 'discount',                                        'd_credit' => 'credit',                                        'd_payterms' => 'payterms',                                        'd_remark' => 'remark',                                        'd_creator' => 'creator',                                        'd_create_datetime' => 'create_datetime',                                        'd_debt1' => 'debt1',                                        'd_produce' => 'produce',                                        'd_debt3' => 'debt3',                                        'd_delivered' => 'delivered',                                        'd_received' => 'received',                                        'd_balance' => 'balance',                                        'd_status' => 'status',                                        'd_start_date' => 'start_date',                                        'd_start' => 'start',
    'concat(a_province, a_city, a_county, d_address)' => 'area',
    'u_truename' => 'owner',
    'ds_label' => 'status_label',
    'd_num' => 'num',
    'd_dealer_rank' => 'dealer_rank',
    'd_down_payment' => 'down_payment'
    );

$config['dealer/dealer_model/is_exist'] = array(
    'd_id' => 'v'
);
$config['dealer/dealer_model/is_public'] = array(
    'd_id' => 'v'
);
