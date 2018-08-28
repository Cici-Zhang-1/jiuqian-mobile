<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/goods_speci_model/select'] = array(
    'gs_id' => 'v',
    'gs_goods_id' => 'goods_id',
    'g_name' => 'goods',
    'gs_speci' => 'speci',
    'gs_code' => 'code',
    'g_purchase_unit' => 'purchase_unit',
    'gs_purchase' => 'purchase',
    'g_unit' => 'unit',
    'gs_unit_price' => 'unit_price',
    'gs_remark' => 'remark',
    'gs_status' => 'status',
    'bt_label' => 'status_label',
    'u_truename' => 'creator',
    'gs_create_datetime' => 'create_datetime',
    's_name' => 'supplier',
    'p_name' => 'product',
    'sgp_unit_price' => 'saler_unit_price'
);

$config['product/goods_speci_model/is_valid_goods_speci'] = array(
    'gs_id' => 'v',
    'g_name' => 'name',
    'gs_speci' => 'speci',
    'gs_code' => 'code',
    'g_purchase_unit' => 'purchase_unit',
    'gs_purchase' => 'purchase',
    'g_unit' => 'unit',
    'ifnull(sgp_unit_price, gs_unit_price)' => 'saler_unit_price',
    'gs_remark' => 'remark',
    'p_name' => 'product'
);

$config['product/goods_speci_model/select_by_product_code'] = array(
    'gs_id' => 'v',
    'g_name' => 'name',
    'gs_speci' => 'speci',
    'gs_code' => 'code',
    'g_purchase_unit' => 'purchase_unit',
    'gs_purchase' => 'purchase',
    'g_unit' => 'unit',
    'ifnull(sgp_unit_price, gs_unit_price)' => 'saler_unit_price',
    'gs_remark' => 'remark',
    'p_name' => 'product'
);
