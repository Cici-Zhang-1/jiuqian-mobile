<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_account_book_model/select'] = array(
    'dab_id' => 'v',
    'dab_flow_num' => 'flow_num',
    'dab_dealer_id' => 'dealer_id',
    'dab_in' => 'in',
    'bt_dealer_account_book_label' => 'in_label',
    'dab_amount' => 'amount',
    'dab_title' => 'title',
    'dab_category' => 'category',
    'dab_source_id' => 'source_id',
    'u_truename' => 'creator',
    'dab_create_datetime' => 'create_datetime',
    'dab_remark' => 'remark',
    'dab_balance' => 'balance',
    'dab_virtual_amount' => 'virtual_amount',
    'dab_virtual_balance' => 'virtual_balance',
    'concat(d_num, "_", d_name, "_" , a_province, a_city, a_county, ifnull(d_address, ""), "_", dl_truename, "_", dl_mobilephone)' => 'dealer'
);
