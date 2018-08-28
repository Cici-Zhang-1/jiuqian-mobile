<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/unqrcode_model/select'] = array(
    'u_id' => 'v',
    'u_num' => 'num',
    'u_order_id' => 'order_id',
    'u_product' => 'product',
    'u_pack' => 'pack',
    'u_pack_detail' => 'pack_detail',
    'u_warehouse_num' => 'warehouse_num',
    'u_creator' => 'creator',
    'u_create_datetime' => 'create_datetime'
);

$config['warehouse/unqrcode_model/select_for_label'] = array(
    'u_id' => 'v',
    'u_num' => 'num',
    'u_order_id' => 'order_id',
    'u_product' => 'product',
    'u_pack' => 'pack',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_delivery_area' => 'delivery_area',
    'o_delivery_address' => 'delivery_address',
    'o_delivery_linker' => 'delivery_linker',
    'o_delivery_phone' => 'delivery_phone'
);

$config['warehouse/unqrcode_model/select_pick_sheet_detail'] = array(
    'u_id' => 'v',
    'u_num' => 'order_product_num',
    'u_warehouse_num' => 'warehouse_v',
    'u_pack_detail' => 'pack_detail',
    'A.scanned' => 'scanned',
    'o_dealer' => 'dealer'
);

$config['warehouse/unqrcode_model/select_pick_sheet_print'] = array(
    'u_id' => 'v',
    'u_num' => 'order_product_num',
    'A.scanned' => 'scanned',
    'u_warehouse_num' => 'warehouse_v',
    'u_pack' => 'order_product_pack',
    'u_pack_detail' => 'pack_detail',
    'u_product' => 'product',
    'o_id' => 'order_v',
    'o_dealer' => 'dealer',
    'o_dealer_id' => 'did',
    'o_delivery_area' => 'delivery_area',
    'o_delivery_address' => 'delivery_address',
    'o_delivery_linker' => 'delivery_linker',
    'o_delivery_phone' => 'delivery_phone',
    'o_logistics' => 'logistics',
    'o_owner' => 'owner',
    'o_sum' => 'sum',
    'if(o_payed_datetime is not null && o_payed_datetime > 0, "已付", o_payterms)' => 'payed',
    'so_end_datetime' => 'end_datetime',
    'so_truck' => 'truck',
    'so_train' => 'train',
    'so_pack' => 'pack',
    'so_collection' => 'collection'
);

$config['warehouse/unqrcode_model/has_brother'] = array(
    'u_num' => 'num'
);

$config['order/order_product_model/is_exist'] = array(
    'u_id' => 'v',
    'o_id' => 'order_v',
    'o_warehouse_num' => 'warehouse_v'
);
