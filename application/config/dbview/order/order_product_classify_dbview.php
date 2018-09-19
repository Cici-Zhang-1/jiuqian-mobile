<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/order_product_classify_model/select'] = array(
                                'opc_id' => 'v',                                        'opc_order_product_id' => 'order_product_id',                                        'opc_board' => 'board',                                        'opc_amount' => 'amount',                                        'opc_area' => 'area',                                        'opc_classify_id' => 'classify_id',                                        'opc_status' => 'status',                                        'opc_optimize' => 'optimize',                                        'opc_sn' => 'sn',                                        'opc_optimize_datetime' => 'optimize_datetime',                                        'opc_optimizer' => 'optimizer'                    );


$config['order/order_product_classify_model/select_optimize'] = array(
    'opc_id' => 'v',
    'opc_board' => 'board',
    'opc_area' => 'area',
    'opc_amount' => 'amount',
    'opc_optimize_datetime' => 'optimize_datetime',
    'O.u_truename' => 'optimize',
    'op_num' => 'num',
    'op_remark' => 'remark',
    'op_product' => 'product',
    'o_id' => 'order_id',
    'o_remark' => 'order_remark',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_request_outdate' => 'request_outdate',
    'od_sure_datetime' => 'sure_datetime',
    'D.u_truename' => 'dismantle',
    'c_name' => 'classify_name'
);

$config['order/order_product_classify_model/select_packable_by_order_product_id'] = array(
    'opc_id' => 'v',
    'opc_status' => 'status',
    'opc_procedure' => 'procedure',
    'opc_pack' => 'pack',
    'opc_pack_datetime' => 'pack_datetime',
    'u_truename' => 'packer'
);

$config['order/order_product_classify_model/has_brothers'] = array(
    'opc_id' => 'v',
    'op_id' => 'order_product_id',
    'op_num' => 'num',
    'op_remark' => 'remark',
    'op_product' => 'product',
    'op_product_id' => 'product_id',
    'o_id' => 'order_id',
    'o_remark' => 'order_remark',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'o_request_outdate' => 'request_outdate',
    'c_name' => 'classify_name'
);

$config['order/order_product_classify_model/select_order_product_id'] = array(
    'opc_order_product_id' => 'order_product_id'
);

$config['order/order_product_classify_model/are_to_able'] = array(
    'opc_order_product_id' => 'order_product_id'
);

$config['order/order_product_classify_model/select_current_workflow'] = array(
    'wp_id' => 'v',
    'wp_name' => 'name',
    'wp_label' => 'label',
    'wp_file' => 'file'
);

$config['order/order_product_classify_model/is_status_and_brothers'] = array(
    'opc_id' => 'v',
    'opc_board' => 'board'
);

$config['order/order_product_classify_model/are_edged_and_brothers'] = array(
    'opc_id' => 'v',
    'opc_board' => 'board'
);

$config['order/order_product_classify_model/are_scanned_and_brothers'] = array(
    'opc_id' => 'v',
    'opc_board' => 'board'
);

$config['order/order_product_classify_model/is_exist_batch_num'] = array(
    'opc_id' => 'v'
);

$config['order/order_product_classify_model/are_status_by_mrp_id'] = array(
    'opc_id' => 'v'
);
