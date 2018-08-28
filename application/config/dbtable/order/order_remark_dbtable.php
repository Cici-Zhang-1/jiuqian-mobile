<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_remark_model'] = array(
                                'order_id' => 'or_order_id',
                                'for' => 'or_for',
                                'remark' => 'or_remark',
                                'creator' => 'or_creator',
                                'create_datetime' => 'or_create_datetime',
                        'status' => 'or_status'
                    );
$config['order/order_remark_model/insert'] = array(
                                'order_id' => 'or_order_id',
                                'for' => 'or_for',
                                'remark' => 'or_remark',
                                'creator' => 'or_creator',
                                'create_datetime' => 'or_create_datetime',
                        'status' => 'or_status'
                    );
$config['order/order_remark_model/insert_batch'] = array(
                                'order_id' => 'or_order_id',
                                'for' => 'or_for',
                                'remark' => 'or_remark',
                                'creator' => 'or_creator',
                                'create_datetime' => 'or_create_datetime',
                        'status' => 'or_status'
                    );
$config['order/order_remark_model/update'] = array(
                                'order_id' => 'or_order_id',
                                'for' => 'or_for',
                                'remark' => 'or_remark',
                        'status' => 'or_status'
                    );
$config['order/order_remark_model/update_batch'] = array(
                                'order_id' => 'or_order_id',
                                'for' => 'or_for',
                                'remark' => 'or_remark',
                                'status' => 'or_status',
                        'v' => 'or_id'
                    );
