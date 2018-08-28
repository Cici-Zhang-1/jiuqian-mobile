<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse_order_product_model'] = array(
                                'warehouse_v' => 'wop_warehouse_num',
                                'order_product_num' => 'wop_order_product_num',
                                'order_num' => 'wop_order_num',
                                'amount' => 'wop_amount',
                                'classify' => 'wop_classify',
                                'creator' => 'wop_creator',
                                'create_datetime' => 'wop_create_datetime',
                                'picker' => 'wop_picker',
                        'pick_datetime' => 'wop_pick_datetime'
                    );
$config['warehouse/warehouse_order_product_model/insert'] = array(
                                'warehouse_v' => 'wop_warehouse_num',
    'order_product_num' => 'wop_order_product_num',
    'order_num' => 'wop_order_num',
    'amount' => 'wop_amount',
    'classify' => 'wop_classify',
                                'creator' => 'wop_creator',
                                'create_datetime' => 'wop_create_datetime'
                    );
$config['warehouse/warehouse_order_product_model/insert_batch'] = array(
                                'warehouse_v' => 'wop_warehouse_num',
    'order_product_num' => 'wop_order_product_num',
    'order_num' => 'wop_order_num',
    'amount' => 'wop_amount',
    'classify' => 'wop_classify',
                                'creator' => 'wop_creator',
                                'create_datetime' => 'wop_create_datetime'
                    );
$config['warehouse/warehouse_order_product_model/insert_ignore_batch'] = array(
    'warehouse_v' => 'wop_warehouse_num',
    'order_product_num' => 'wop_order_product_num',
    'order_num' => 'wop_order_num',
    'amount' => 'wop_amount',
    'classify' => 'wop_classify',
    'creator' => 'wop_creator',
    'create_datetime' => 'wop_create_datetime'
);
$config['warehouse/warehouse_order_product_model/replace_batch'] = array(
    'warehouse_v' => 'wop_warehouse_num',
    'order_product_num' => 'wop_order_product_num',
    'order_num' => 'wop_order_num',
    'amount' => 'wop_amount',
    'classify' => 'wop_classify',
    'creator' => 'wop_creator',
    'create_datetime' => 'wop_create_datetime'
);
$config['warehouse/warehouse_order_product_model/update'] = array(
                                'warehouse_v' => 'wop_warehouse_num',
    'order_product_num' => 'wop_order_product_num',
    'order_num' => 'wop_order_num',
    'amount' => 'wop_amount',
    'classify' => 'wop_classify',
                                'picker' => 'wop_picker',
                        'pick_datetime' => 'wop_pick_datetime'
                    );
$config['warehouse/warehouse_order_product_model/update_move'] = array(
    'warehouse_v' => 'wop_warehouse_num',
    'order_product_num' => 'wop_order_product_num',
    'order_num' => 'wop_order_num',
    'amount' => 'wop_amount',
    'classify' => 'wop_classify',
    'picker' => 'wop_picker',
    'pick_datetime' => 'wop_pick_datetime'
);
$config['warehouse/warehouse_order_product_model/update_out'] = array(
    'picker' => 'wop_picker',
    'pick_datetime' => 'wop_pick_datetime'
);
$config['warehouse/warehouse_order_product_model/update_batch'] = array(
                                'warehouse_v' => 'wop_warehouse_num',
    'order_product_num' => 'wop_order_product_num',
    'order_num' => 'wop_order_num',
    'amount' => 'wop_amount',
    'classify' => 'wop_classify',
                                'picker' => 'wop_picker',
                        'pick_datetime' => 'wop_pick_datetime'
                    );
