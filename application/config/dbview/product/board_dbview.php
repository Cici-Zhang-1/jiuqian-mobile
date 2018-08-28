<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/board_model/select'] = array(
                                'b_name' => array(
                                                                    'name',                                                        'v'                                                ),
    'b_length' => 'length',
    'b_width' => 'width',
    'b_thick' => 'thick',
    'b_color' => 'color',
    'b_nature' => 'nature',
    'b_class' => 'class',
    'b_purchase' => 'purchase',
    'b_unit_price' => 'unit_price',
    'b_amount' => 'amount',
    'b_supplier_id' => 'supplier_id',
    's_name' => 'supplier',
    'b_remark' => 'remark',
    'u_truename' => 'creator',
    'b_create_datetime' => 'create_datetime',
    'b_status' => 'status',
    'bt_label' => 'status_label',
    'sbp_unit_price' => 'saler_unit_price'
);

$config['product/board_model/_select'] = array(
    'b_name' => array(
        'name',                                                        'v'                                                ),
    'b_purchase' => 'purchase',
    'ifnull(sbp_unit_price, b_unit_price)' => 'saler_unit_price',
    'b_thick' => 'thick'
);
