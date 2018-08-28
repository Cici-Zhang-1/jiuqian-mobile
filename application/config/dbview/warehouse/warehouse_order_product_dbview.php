<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse_order_product_model/select'] = array(
    'wop_id' => 'v',
    'wop_warehouse_num' => 'warehouse_v',
    'wop_order_product_num' => 'order_product_num',
    'wop_order_num' => 'order_num',
    'wop_amount' => 'amount',
    'wop_classify' => 'classify',
    'CREATOR.u_truename' => 'creator',
    'wop_create_datetime' => 'create_datetime',
    'PICKER.u_truename' => 'picker',
    'wop_pick_datetime' => 'pick_datetime'
);

$config['warehouse/warehouse_order_product_model/select_order_product_inned'] = array(
    'op_id' => 'order_product_v',
    'wop_warehouse_num' => 'warehouse_v',
);

$config['warehouse/warehouse_order_product_model/select_order_inned'] = array(
    'o_id' => 'order_v',
    'wop_warehouse_num' => 'warehouse_v',
);

$config['warehouse/warehouse_order_product_model/is_in'] = array(
    'wop_id' => 'v',
    'wop_warehouse_num' => 'warehouse_v',
    'op_num' => 'order_product_num',
    'o_num' => 'order_num',
    'op_warehouse_num' => 'order_product_warehouse_num',
    'o_warehouse_num' => 'order_warehouse_num'
);

$config['warehouse/warehouse_order_product_model/is_in_by_stock_outted_v'] = array(
    'wop_id' => 'v',
    'wop_warehouse_num' => 'warehouse_v'
);
