<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_order_model/select'] = array(
                                'wo_id' => array(
                                                                    'id',                                                        'v'                                                ),                                        'wo_name' => 'name',                                        'wo_label' => 'label',                                        'wo_previous' => 'previous',                                        'wo_next' => 'next',                                        'wo_file' => 'file'                    );

$config['workflow/workflow_order_model/select_by_name'] = array(
    'wo_id' => array(
        'id',
        'v'
    ),
    'wo_name' => 'name',
    'wo_label' => 'label',
    'wo_previous' => 'previous',
    'wo_next' => 'next',
    'wo_file' => 'file'
);