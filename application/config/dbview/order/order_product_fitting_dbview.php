<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_fitting_model/select'] = array(
    'opf_id' => 'v',
    'opf_goods_speci_id' => 'goods_speci_id',
    'opf_fitting' => array(
        'fitting',
        'name'
    ),
    'opf_speci' => 'speci',
    'opf_unit' => 'unit',
    'opf_amount' => 'amount',
    'opf_unit_price' => 'unit_price',
    'opf_sum' => 'sum',
    'opf_remark' => 'remark',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'op_product' => 'product',
    'op_remark' => 'order_product_remark',
    'op_pack' => 'pack',
    'wop_label' => 'status_label',
    'p_code' => 'code'
);

$config['order/order_product_fitting_model/select_for_sure'] = array(
    'opf_id' => 'v'
);

$config['order/order_product_fitting_model/select_by_order_product_id'] = array(
    'opf_id' => 'v',
    'opf_goods_speci_id' => 'goods_speci_id',
    'opf_fitting' => 'fitting',
    'opf_speci' => 'speci',
    'opf_unit' => 'unit',
    'opf_amount' => 'amount',
    'opf_unit_price' => 'unit_price',
    'opf_sum' => 'sum',
    'opf_remark' => 'remark'
);
