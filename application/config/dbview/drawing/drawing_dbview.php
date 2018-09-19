<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['drawing/drawing_model/select'] = array(
                                'd_id' => 'v',
    'd_name' => 'name',
    'd_type' => 'type',
    'd_order_product_id' => 'order_product_id',
    'd_path' => 'path',
    'd_create_datetime' => 'create_datetime',
    'd_creator' => 'creator',
    'op_num' => 'num',
    'op_product' => 'product'
);


$config['drawing/drawing_model/select_by_order_product_id'] = array(
    'd_path' => 'path'
);
