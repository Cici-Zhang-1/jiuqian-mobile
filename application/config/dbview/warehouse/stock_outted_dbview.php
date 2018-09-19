<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/stock_outted_model/select'] = array(
    'so_id' => 'v',
    'so_num' => 'num',
    'so_truck' => 'truck',
    'so_train' => 'train',
    'so_end_datetime' => 'end_datetime',
    'so_pack' => 'pack',
    'so_delivered_pack' => 'delivered_pack',
    'so_collection' => 'collection',
    'PRINTER.u_truename' => 'printer',
    'so_print_datetime' => 'print_datetime',
    'CREATOR.u_truename' => 'creator',
    'so_create_datetime' => 'create_datetime',
    'so_status' => 'status',
    'sos_label' => 'status_label'
);

$config['warehouse/stock_outted_model/is_exist'] = array(
    'so_id' => 'v',
    'so_num' => 'num',
    'so_truck' => 'truck',
    'so_train' => 'train',
    'so_end_datetime' => 'end_datetime',
    'so_pack' => 'pack',
    'so_pack_detail' => 'pack_detail',
    'so_delivered_pack' => 'delivered_pack',
    'so_collection' => 'collection',
    'so_printer' => 'printer',
    'so_print_datetime' => 'print_datetime',
    'so_creator' => 'creator',
    'so_create_datetime' => 'create_datetime'
);

$config['warehouse/stock_outted_model/is_picked'] = array(
    'so_id' => 'v',
    'so_num' => 'num',
    'so_truck' => 'truck',
    'so_train' => 'train',
    'so_end_datetime' => 'end_datetime',
    'so_pack' => 'pack',
    'so_pack_detail' => 'pack_detail',
    'so_delivered_pack' => 'delivered_pack',
    'so_collection' => 'collection',
    'so_printer' => 'printer',
    'so_print_datetime' => 'print_datetime',
    'so_creator' => 'creator',
    'so_create_datetime' => 'create_datetime'
);

$config['warehouse/stock_outted_model/is_pickable'] = array(
    'so_id' => 'v',
    'so_num' => 'num',
    'so_truck' => 'truck',
    'so_train' => 'train',
    'so_end_datetime' => 'end_datetime',
    'so_pack' => 'pack',
    'so_pack_detail' => 'pack_detail',
    'so_delivered_pack' => 'delivered_pack',
    'so_collection' => 'collection',
    'so_printer' => 'printer',
    'so_print_datetime' => 'print_datetime',
    'so_creator' => 'creator',
    'so_create_datetime' => 'create_datetime',
    'oso_order_id' => 'order_id'
);
