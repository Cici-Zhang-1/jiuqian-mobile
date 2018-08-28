<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/saler_goods_price_model'] = array(
                                'goods_speci_id' => 'sgp_goods_speci_id',
                                'unit_price' => 'sgp_unit_price',
                                'creator' => 'sgp_creator',
                        'create_datetime' => 'sgp_create_datetime'
                    );
$config['product/saler_goods_price_model/insert'] = array(
                                'goods_speci_id' => 'sgp_goods_speci_id',
                                'unit_price' => 'sgp_unit_price',
                                'creator' => 'sgp_creator',
                        'create_datetime' => 'sgp_create_datetime'
                    );
$config['product/saler_goods_price_model/insert_batch'] = array(
                                'goods_speci_id' => 'sgp_goods_speci_id',
                                'unit_price' => 'sgp_unit_price',
                                'creator' => 'sgp_creator',
                        'create_datetime' => 'sgp_create_datetime'
                    );
$config['product/saler_goods_price_model/update'] = array(
                                'goods_speci_id' => 'sgp_goods_speci_id',
                        'unit_price' => 'sgp_unit_price'
                    );
$config['product/saler_goods_price_model/update_batch'] = array(
                                'goods_speci_id' => 'sgp_goods_speci_id',
                                'unit_price' => 'sgp_unit_price',
                        'v' => 'sgp_id'
                    );
