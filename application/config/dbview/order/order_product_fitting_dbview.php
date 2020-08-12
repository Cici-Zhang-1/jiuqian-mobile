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
    'wp_label' => 'procedure_status_label',
    'p_code' => 'code'
);

$config['order/order_product_fitting_model/select_for_post_sale'] = array(
    'opf_status' => 'status',
    'opf_procedure' => 'procedure',
    'opf_production_line' => 'production_line',
    'opf_print' => 'print',
    'opf_print_datetime' => 'print_datetime',
    'opf_pack' => 'pack',
    'opf_pack_datetime' => 'pack_datetime'
);

$config['order/order_product_fitting_model/select_for_sure'] = array(
    'opf_id' => 'v'
);

$config['order/order_product_fitting_model/select_produce'] = array(
    'op_id' => 'v',
    'op_num' => 'num',
    'op_remark' => 'remark',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'od_sure_datetime' => 'sure_datetime',
    'o_request_outdate' => 'request_outdate',
    'o_remark' => 'order_remark'
);

$config['order/order_product_fitting_model/select_produce_by_order_product_id'] = array(
    'opf_id' => 'v',
    'opf_fitting' => 'name',
    'opf_speci' => 'speci',
    'opf_unit' => 'unit',
    'opf_amount' => 'amount',
    'opf_remark' => 'remark',
    'opf_status' => 'status',
    'op_num' => 'order_product_num',
    'o_dealer' => 'dealer',
    's_code' => 'code'
);

$config['order/order_product_fitting_model/select_by_order_product_id'] = array(
    'opf_id' => 'v',
    'opf_goods_speci_id' => 'goods_speci_id',
    'opf_fitting' => 'fitting',
    'opf_speci' => 'speci',
    'opf_unit' => 'unit',
    'opf_amount' => 'amount',
    'opf_unit_price' => 'unit_price',
    'opf_purchase' => 'purchase',
    'opf_sum' => 'sum',
    'opf_remark' => 'remark'
);

$config['order/order_product_fitting_model/select_sales'] = array(
    'opf_id' => 'v',
    'opf_sum' => 'sum',
    'o_dealer_id' => 'dealer_id',
    'o_dealer' => 'dealer',
    'p_id' => 'product_id',
    'p_name' => 'product'
);

$config['order/order_product_fitting_model/select_current_workflow'] = array(
    'wp_id' => 'v',
    'wp_name' => 'name',
    'wp_label' => 'label',
    'wp_file' => 'file'
);

$config['order/order_product_fitting_model/select_order_product_id'] = array(
    'opf_order_product_id' => 'order_product_id'
);

$config['order/order_product_fitting_model/select_packable_by_order_product_id'] = array(
    'opf_id' => 'v',
    'opf_status' => 'status',
    'opf_procedure' => 'procedure'
);
