<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/pick_scan_model'] = array(
                                'order_product_num' => 'ps_order_product_num',
                                'pack' => 'ps_pack',
                                'num' => 'ps_num',
                                'classify' => 'ps_classify',
                                'creator' => 'ps_creator',
                        'create_datetime' => 'ps_create_datetime'
                    );
$config['warehouse/pick_scan_model/insert'] = array(
                                'order_product_num' => 'ps_order_product_num',
    'stock_outted_v' => 'ps_stock_outted_id',
                                'pack' => 'ps_pack',
                                'num' => 'ps_num',
                                'classify' => 'ps_classify',
                                'creator' => 'ps_creator',
                        'create_datetime' => 'ps_create_datetime'
                    );
$config['warehouse/pick_scan_model/insert_batch'] = array(
                                'order_product_num' => 'ps_order_product_num',
    'stock_outted_v' => 'ps_stock_outted_id',
                                'pack' => 'ps_pack',
                                'num' => 'ps_num',
                                'classify' => 'ps_classify',
                                'creator' => 'ps_creator',
                        'create_datetime' => 'ps_create_datetime'
                    );
$config['warehouse/pick_scan_model/insert_ignore'] = array(
    'order_product_num' => 'ps_order_product_num',
    'stock_outted_v' => 'ps_stock_outted_id',
    'qrcode' => 'ps_qrcode',
    'pack' => 'ps_pack',
    'num' => 'ps_num',
    'classify' => 'ps_classify',
    'creator' => 'ps_creator',
    'create_datetime' => 'ps_create_datetime'
);
$config['warehouse/pick_scan_model/replace_batch'] = array(
    'order_product_num' => 'ps_order_product_num',
    'stock_outted_v' => 'ps_stock_outted_id',
    'qrcode' => 'ps_qrcode',
    'pack' => 'ps_pack',
    'num' => 'ps_num',
    'classify' => 'ps_classify',
    'creator' => 'ps_creator',
    'create_datetime' => 'ps_create_datetime'
);
$config['warehouse/pick_scan_model/update'] = array(
                                'order_product_num' => 'ps_order_product_num',
                                'pack' => 'ps_pack',
                                'num' => 'ps_num',
                                'classify' => 'ps_classify',
                                'creator' => 'ps_creator',
                        'create_datetime' => 'ps_create_datetime'
                    );
$config['warehouse/pick_scan_model/update_batch'] = array(
                                'order_product_num' => 'ps_order_product_num',
                                'pack' => 'ps_pack',
                                'num' => 'ps_num',
                                'classify' => 'ps_classify',
                                'creator' => 'ps_creator',
                        'create_datetime' => 'ps_create_datetime'
                    );
