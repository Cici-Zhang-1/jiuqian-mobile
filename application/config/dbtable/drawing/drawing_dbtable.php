<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['drawing/drawing_model'] = array(
                                'name' => 'd_name',
                                'type' => 'd_type',
                                'order_product_id' => 'd_order_product_id',
                                'path' => 'd_path',
                                'create_datetime' => 'd_create_datetime',
                        'creator' => 'd_creator'
                    );
$config['drawing/drawing_model/insert'] = array(
                                'name' => 'd_name',
                                'type' => 'd_type',
                                'order_product_id' => 'd_order_product_id',
                                'path' => 'd_path',
                                'create_datetime' => 'd_create_datetime',
                        'creator' => 'd_creator'
                    );
$config['drawing/drawing_model/insert_batch'] = array(
                                'name' => 'd_name',
                                'type' => 'd_type',
                                'order_product_id' => 'd_order_product_id',
                                'path' => 'd_path',
                                'create_datetime' => 'd_create_datetime',
                        'creator' => 'd_creator'
                    );
$config['drawing/drawing_model/update'] = array(
                                'name' => 'd_name',
                                'type' => 'd_type',
                                'order_product_id' => 'd_order_product_id',
                        'path' => 'd_path'
                    );
$config['drawing/drawing_model/update_batch'] = array(
                                'name' => 'd_name',
                                'type' => 'd_type',
                                'order_product_id' => 'd_order_product_id',
                                'path' => 'd_path',
                        'v' => 'd_id'
                    );
