<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_order_msg_model'] = array(
                                'order_id' => 'wom_order_id',
                                'workflow_order_id' => 'wom_workflow_order_id',
                                'msg' => 'wom_msg',
                                'previous' => 'wom_previous',
                                'creator' => 'wom_creator',
                        'create_datetime' => 'wom_create_datetime'
                    );
$config['workflow/workflow_order_msg_model/insert'] = array(
                                'order_id' => 'wom_order_id',
                                'workflow_order_id' => 'wom_workflow_order_id',
                                'msg' => 'wom_msg',
                                'previous' => 'wom_previous',
                                'creator' => 'wom_creator',
                        'create_datetime' => 'wom_create_datetime',
    'timestamp' => 'wom_timestamp'
                    );
$config['workflow/workflow_order_msg_model/insert_batch'] = array(
                                'order_id' => 'wom_order_id',
                                'workflow_order_id' => 'wom_workflow_order_id',
                                'msg' => 'wom_msg',
                                'previous' => 'wom_previous',
                                'creator' => 'wom_creator',
                        'create_datetime' => 'wom_create_datetime',
    'timestamp' => 'wom_timestamp'
                    );
$config['workflow/workflow_order_msg_model/update'] = array(
                                'order_id' => 'wom_order_id',
                                'workflow_order_id' => 'wom_workflow_order_id',
                                'msg' => 'wom_msg',
                        'previous' => 'wom_previous'
                    );
$config['workflow/workflow_order_msg_model/update_batch'] = array(
                                'order_id' => 'wom_order_id',
                                'workflow_order_id' => 'wom_workflow_order_id',
                                'msg' => 'wom_msg',
                                'previous' => 'wom_previous',
                        'v' => 'wom_id'
                    );
