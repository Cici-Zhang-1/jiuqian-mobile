<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/shop_model'] = array(
    'num' => 's_num',
                                'name' => 's_name',
                                'dealer_id' => 's_dealer_id',
                                'area_id' => 's_area_id',
                                'address' => 's_address',
                                'debt1' => 's_debt1',
                                'debt2' => 's_debt2',
                                'debt3' => 's_debt3',
                                'delivered' => 's_delivered',
                                'received' => 's_received',
                                'balance' => 's_balance',
                                'remark' => 's_remark',
                                'creator' => 's_creator',
                        'create_datetime' => 's_create_datetime'
                    );
$config['dealer/shop_model/insert'] = array(
    'num' => 's_num',
                                'name' => 's_name',
                                'dealer_id' => 's_dealer_id',
                                'area_id' => 's_area_id',
                                'address' => 's_address',
                                'remark' => 's_remark',
                                'creator' => 's_creator',
                        'create_datetime' => 's_create_datetime'
                    );
$config['dealer/shop_model/insert_batch'] = array(
    'num' => 's_num',
                                'name' => 's_name',
                                'dealer_id' => 's_dealer_id',
                                'area_id' => 's_area_id',
                                'address' => 's_address',
                                'remark' => 's_remark',
                                'creator' => 's_creator',
                        'create_datetime' => 's_create_datetime'
                    );
$config['dealer/shop_model/update'] = array(
                                'name' => 's_name',
                                'dealer_id' => 's_dealer_id',
                                'area_id' => 's_area_id',
                                'address' => 's_address',
                                'debt1' => 's_debt1',
                                'debt2' => 's_debt2',
                                'debt3' => 's_debt3',
                                'delivered' => 's_delivered',
                                'received' => 's_received',
                                'balance' => 's_balance',
                                'remark' => 's_remark'
                    );
$config['dealer/shop_model/update_batch'] = array(
    'v' => 's_id',
                                'name' => 's_name',
                                'dealer_id' => 's_dealer_id',
                                'area_id' => 's_area_id',
                                'address' => 's_address',
                                'debt1' => 's_debt1',
                                'debt2' => 's_debt2',
                                'debt3' => 's_debt3',
                                'delivered' => 's_delivered',
                                'received' => 's_received',
                                'balance' => 's_balance',
                                'remark' => 's_remark'
                    );
