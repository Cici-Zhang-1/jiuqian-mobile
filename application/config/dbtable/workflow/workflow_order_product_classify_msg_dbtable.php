<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_order_product_classify_msg_model'] = array(
                                'order_product_classify_id' => 'wopcm_order_product_classify_id',
                                'creator' => 'wopcm_creator',
                                'create_datetime' => 'wopcm_create_datetime',
                                'msg' => 'wopcm_msg',
                                'workflow_order_product_classify_id' => 'wopcm_workflow_order_product_classify_id',
                        'previous' => 'wopcm_previous'
                    );
$config['workflow/workflow_order_product_classify_msg_model/insert'] = array(
                                'order_product_classify_id' => 'wopcm_order_product_classify_id',
                                'creator' => 'wopcm_creator',
                                'create_datetime' => 'wopcm_create_datetime',
                                'msg' => 'wopcm_msg',
                                'workflow_order_product_classify_id' => 'wopcm_workflow_order_product_classify_id',
                        'previous' => 'wopcm_previous',
    'timestamp' => 'wopcm_timestamp'
                    );
$config['workflow/workflow_order_product_classify_msg_model/insert_batch'] = array(
                                'order_product_classify_id' => 'wopcm_order_product_classify_id',
                                'creator' => 'wopcm_creator',
                                'create_datetime' => 'wopcm_create_datetime',
                                'msg' => 'wopcm_msg',
                                'workflow_order_product_classify_id' => 'wopcm_workflow_order_product_classify_id',
                        'previous' => 'wopcm_previous',
    'timestamp' => 'wopcm_timestamp'
                    );
$config['workflow/workflow_order_product_classify_msg_model/update'] = array(
                                'order_product_classify_id' => 'wopcm_order_product_classify_id',
                                'msg' => 'wopcm_msg',
                                'workflow_order_product_classify_id' => 'wopcm_workflow_order_product_classify_id',
                        'previous' => 'wopcm_previous'
                    );
$config['workflow/workflow_order_product_classify_msg_model/update_batch'] = array(
                                'order_product_classify_id' => 'wopcm_order_product_classify_id',
                                'msg' => 'wopcm_msg',
                                'workflow_order_product_classify_id' => 'wopcm_workflow_order_product_classify_id',
                                'previous' => 'wopcm_previous',
                        'v' => 'wopcm_id'
                    );
