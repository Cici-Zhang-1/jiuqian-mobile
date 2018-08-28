<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_order_product_model/select'] = array(
                                'wop_id' => array(
                                                                    'id',                                                        'v'                                                ),                                        'wop_name' => 'name',                                        'wop_label' => 'label',                                        'wop_previous' => 'previous',                                        'wop_next' => 'next',                                        'wop_file' => 'file'                    );

$config['workflow/workflow_order_product_model/select_by_name'] = array(
    'wop_id' => array(
        'id',
        'v'
    ),
    'wop_name' => 'name',
    'wop_label' => 'label',
    'wop_previous' => 'previous',
    'wop_next' => 'next',
    'wop_file' => 'file'
);