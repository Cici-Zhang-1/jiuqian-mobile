<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/order_stock_outted_model/select'] = array(
    'oso_id' => array(
        'id',
        'v'
    ),
    'oso_order_id' => 'order_id',
    'oso_stock_outted_id' => 'stock_outted_id',
    'o_num' => 'num',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_pack' => 'pack',
    'o_delivered' => 'delivered',
    'so_end_datetime' => 'end_datetime',
    'so_truck' => 'truck',
    'so_train' => 'train',
    'so_create_datetime' => 'create_datetime',
    'u_truename' => 'creator'
);

$config['warehouse/order_stock_outted_model/are_re_deliverable_by_stock_outted_id'] = array(
    'oso_id' => 'v',
    'oso_order_id' => 'order_id',
    'oso_stock_outted_id' => 'stock_outted_id',
    'o_delivered' => 'delivered',
    'so_pack' => 'pack',
    'so_pack_detail' => 'pack_detail'
);

$config['warehouse/order_stock_outted_model/is_re_deliverable'] = array(
    'oso_id' => 'v',
    'oso_order_id' => 'order_id',
    'oso_stock_outted_id' => 'stock_outted_id',
    'o_delivered' => 'delivered',
    'so_pack' => 'pack',
    'so_pack_detail' => 'pack_detail'
);
