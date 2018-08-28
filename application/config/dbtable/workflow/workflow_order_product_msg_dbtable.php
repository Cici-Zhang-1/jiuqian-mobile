<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_order_product_msg_model'] = array(
                                'order_product_id' => 'wopm_order_product_id',
                                'workflow_order_product_id' => 'wopm_workflow_order_product_id',
                                'msg' => 'wopm_msg',
                                'previous' => 'wopm_previous',
                                'creator' => 'wopm_creator',
                        'create_datetime' => 'wopm_create_datetime'
                    );
$config['workflow/workflow_order_product_msg_model/insert'] = array(
                                'order_product_id' => 'wopm_order_product_id',
                                'workflow_order_product_id' => 'wopm_workflow_order_product_id',
                                'msg' => 'wopm_msg',
                                'previous' => 'wopm_previous',
                                'creator' => 'wopm_creator',
                        'create_datetime' => 'wopm_create_datetime',
    'timestamp' => 'wopm_timestamp'
                    );
$config['workflow/workflow_order_product_msg_model/insert_batch'] = array(
                                'order_product_id' => 'wopm_order_product_id',
                                'workflow_order_product_id' => 'wopm_workflow_order_product_id',
                                'msg' => 'wopm_msg',
                                'previous' => 'wopm_previous',
                                'creator' => 'wopm_creator',
                        'create_datetime' => 'wopm_create_datetime',
    'timestamp' => 'wopm_timestamp'
                    );
$config['workflow/workflow_order_product_msg_model/update'] = array(
                                'order_product_id' => 'wopm_order_product_id',
                                'workflow_order_product_id' => 'wopm_workflow_order_product_id',
                                'msg' => 'wopm_msg',
                        'previous' => 'wopm_previous'
                    );
$config['workflow/workflow_order_product_msg_model/update_batch'] = array(
                                'order_product_id' => 'wopm_order_product_id',
                                'workflow_order_product_id' => 'wopm_workflow_order_product_id',
                                'msg' => 'wopm_msg',
                                'previous' => 'wopm_previous',
                        'v' => 'wopm_id'
                    );
