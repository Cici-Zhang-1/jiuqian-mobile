<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_remark_model'] = array(
                                'order_product_id' => 'opr_order_product_id',
                                'status' => 'opr_status',
                                'remark' => 'opr_remark',
                                'creator' => 'opr_creator',
                        'create_datetime' => 'opr_create_datetime'
                    );
$config['order/order_product_remark_model/insert'] = array(
                                'order_product_id' => 'opr_order_product_id',
                                'status' => 'opr_status',
                                'remark' => 'opr_remark',
                                'creator' => 'opr_creator',
                        'create_datetime' => 'opr_create_datetime'
                    );
$config['order/order_product_remark_model/insert_batch'] = array(
                                'order_product_id' => 'opr_order_product_id',
                                'status' => 'opr_status',
                                'remark' => 'opr_remark',
                                'creator' => 'opr_creator',
                        'create_datetime' => 'opr_create_datetime'
                    );
$config['order/order_product_remark_model/update'] = array(
                                'order_product_id' => 'opr_order_product_id',
                                'status' => 'opr_status',
                        'remark' => 'opr_remark'
                    );
$config['order/order_product_remark_model/update_batch'] = array(
                                'order_product_id' => 'opr_order_product_id',
                                'status' => 'opr_status',
                                'remark' => 'opr_remark',
                        'v' => 'opr_id'
                    );
