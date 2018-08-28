<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/saler_board_price_model'] = array(
                                'board' => 'sbp_board',
                                'unit_price' => 'sbp_unit_price',
                                'creator' => 'sbp_creator',
                        'create_datetime' => 'sbp_create_datetime'
                    );
$config['product/saler_board_price_model/insert'] = array(
                                'board' => 'sbp_board',
                                'unit_price' => 'sbp_unit_price',
                                'creator' => 'sbp_creator',
                        'create_datetime' => 'sbp_create_datetime'
                    );
$config['product/saler_board_price_model/insert_batch'] = array(
                                'board' => 'sbp_board',
                                'unit_price' => 'sbp_unit_price',
                                'creator' => 'sbp_creator',
                        'create_datetime' => 'sbp_create_datetime'
                    );
$config['product/saler_board_price_model/insert_batch_update'] = array(
    'board' => 'sbp_board',
    'unit_price' => 'sbp_unit_price',
    'creator' => 'sbp_creator',
    'create_datetime' => 'sbp_create_datetime'
);
$config['product/saler_board_price_model/update'] = array(
                                'board' => 'sbp_board',
                        'unit_price' => 'sbp_unit_price'
                    );
$config['product/saler_board_price_model/update_batch'] = array(
                                'board' => 'sbp_board',
                                'unit_price' => 'sbp_unit_price',
                        'v' => 'sbp_id'
                    );
