<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/remark_list_model'] = array(
                        'order_id' => 'rl_order_id'
                    );
$config['order/remark_list_model/insert'] = array(
                        'order_id' => 'rl_order_id'
                    );
$config['order/remark_list_model/insert_batch'] = array(
                        'order_id' => 'rl_order_id'
                    );
$config['order/remark_list_model/update'] = array(
                        'order_id' => 'rl_order_id'
                    );
$config['order/remark_list_model/update_batch'] = array(
                                'order_id' => 'rl_order_id',
                        'v' => 'rl_id'
                    );
