<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/mrp_model/select'] = array(
    'm_id' => 'v',
    'm_batch_num' => 'batch_num',
    'm_board' => 'board',
    'wm_label' => 'status',
    'sum(m_num)' => 'num',
    'u_truename' => 'distribution'
);

$config['order/mrp_model/select_electronic_saw'] = array(
    'm_id' => 'v',
    'm_batch_num' => 'batch_num',
    'm_board' => 'board',
    'sum(m_num)' => 'num',
    'S.u_truename' => 'shear',
    'm_shear_datetime' => 'shear_datetime',
    'E.u_truename' => 'saw',
    'm_saw_datetime' => 'saw_datetime'
);

$config['order/mrp_model/select_electronic_sawed'] = array(
    'm_id' => 'v',
    'm_board' => 'board',
    'm_num' => 'num',
    'E.u_truename' => 'saw',
    'm_saw_datetime' => 'saw_datetime',
    'b_thick' => 'thick'
);

$config['order/mrp_model/select_user_current_work'] = array(
    'm_id' => 'v'
);
$config['order/mrp_model/is_electronic_saw'] = array(
    'm_id' => 'v'
);

$config['order/mrp_model/is_status_and_brothers'] = array(
    'm_id' => 'v',
    'm_batch_num' => 'batch_num',
    'm_board' => 'board'
);

$config['order/mrp_model/is_distributable'] = array(
    'm_id' => 'v',
    'm_board' => 'board'
);

$config['order/mrp_model/is_exist_batch_num'] = array(
    'm_id' => 'v',
    'm_status' => 'status'
);

$config['order/mrp_model/is_exist'] = array(
    'm_id' => 'v',
    'm_status' => 'status'
);

$config['order/mrp_model/select_order_product_v_by_v'] = array(
    'opc_order_product_id' => 'order_product_v'
);

$config['order/mrp_model/select_order_product_classify_id'] = array(
    'opc_id' => 'order_product_classify_id'
);

$config['order/mrp_model/select_produce_process_tracking'] = array(
    'op_id' => 'v',
    'op_num' => 'order_product_num',
    'op_product' => 'product',
    'o_dealer' => 'dealer',
    'o_owner' => 'owner',
    'u_truename' => 'creator',
    'wmm_create_datetime' => 'electronic_saw_datetime'
);

$config['order/mrp_model/select_current_workflow'] = array(
    'wm_id' => 'v',
    'wm_type' => 'type',
    'wm_name' => 'name',
    'wm_label' => 'label',
    'wm_previous' => 'previous',
    'wm_next' => 'next',
    'wm_file' => 'file'
);
