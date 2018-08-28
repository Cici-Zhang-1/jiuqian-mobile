<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/mrp_model'] = array(
                                'batch_num' => 'm_batch_num',
                                'board' => 'm_board',
                                'status' => 'm_status',
                                'num' => 'm_num',
                        'distribution' => 'm_distribution'
                    );
$config['order/mrp_model/insert'] = array(
                                'batch_num' => 'm_batch_num',
                                'board' => 'm_board',
                                'status' => 'm_status',
                                'num' => 'm_num',
                        'distribution' => 'm_distribution'
                    );
$config['order/mrp_model/insert_batch'] = array(
                                'batch_num' => 'm_batch_num',
                                'board' => 'm_board',
                                'status' => 'm_status',
                                'num' => 'm_num',
                        'distribution' => 'm_distribution'
                    );
$config['order/mrp_model/update'] = array(
                                'batch_num' => 'm_batch_num',
                                'board' => 'm_board',
                                'status' => 'm_status',
                                'num' => 'm_num',
                        'distribution' => 'm_distribution',
    'shear' => 'm_shear',
    'shear_datetime' => 'm_shear_datetime',
    'saw' => 'm_saw',
    'saw_datetime' => 'm_saw_datetime'
                    );
$config['order/mrp_model/update_batch'] = array(
    'v' => 'm_id',
                                'batch_num' => 'm_batch_num',
                                'board' => 'm_board',
                                'status' => 'm_status',
                                'num' => 'm_num',
                        'distribution' => 'm_distribution',
    'shear' => 'm_shear',
    'shear_datetime' => 'm_shear_datetime',
    'saw' => 'm_saw',
    'saw_datetime' => 'm_saw_datetime'
                    );
