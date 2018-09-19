<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/unqrcode_model/select'] = array(
    'U.u_id' => 'v',
    'U.u_num' => 'num',
    'U.u_order_id' => 'order_id',
    'U.u_product' => 'product',
    'U.u_pack' => 'pack',
    'U.u_pack_detail' => 'pack_detail',
    'U.u_warehouse_num' => 'warehouse_num',
    'C.u_truename' => 'creator',
    'U.u_create_datetime' => 'create_datetime'
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
    'u_pack' => 'pack',
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
    'o_virtual_sum' => 'virtual_sum',
    'o_dealer_remark' => 'dealer_remark',
    'ps_label' => 'payed',
    'o_collection' => 'collection'
);

$config['warehouse/unqrcode_model/has_brother'] = array(
    'u_num' => 'num'
);

$config['order/order_product_model/is_exist'] = array(
    'u_id' => 'v',
    'o_id' => 'order_v',
    'o_warehouse_num' => 'warehouse_v'
);
