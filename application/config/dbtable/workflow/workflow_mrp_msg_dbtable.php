<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_mrp_msg_model'] = array(
                                'mrp_id' => 'wmm_mrp_id',
                                'creator' => 'wmm_creator',
                                'create_datetime' => 'wmm_create_datetime',
                                'msg' => 'wmm_msg',
                                'workflow_mrp_id' => 'wmm_workflow_mrp_id',
                        'previous' => 'wmm_previous'
                    );
$config['workflow/workflow_mrp_msg_model/insert'] = array(
                                'mrp_id' => 'wmm_mrp_id',
                                'creator' => 'wmm_creator',
                                'create_datetime' => 'wmm_create_datetime',
                                'msg' => 'wmm_msg',
                                'workflow_mrp_id' => 'wmm_workflow_mrp_id',
                        'previous' => 'wmm_previous',
    'timestamp' => 'wmm_timestamp'
                    );
$config['workflow/workflow_mrp_msg_model/insert_batch'] = array(
                                'mrp_id' => 'wmm_mrp_id',
                                'creator' => 'wmm_creator',
                                'create_datetime' => 'wmm_create_datetime',
                                'msg' => 'wmm_msg',
                                'workflow_mrp_id' => 'wmm_workflow_mrp_id',
                        'previous' => 'wmm_previous',
    'timestamp' => 'wmm_timestamp'
                    );
$config['workflow/workflow_mrp_msg_model/update'] = array(
                                'mrp_id' => 'wmm_mrp_id',
                                'creator' => 'wmm_creator',
                                'create_datetime' => 'wmm_create_datetime',
                                'msg' => 'wmm_msg',
                                'workflow_mrp_id' => 'wmm_workflow_mrp_id',
                        'previous' => 'wmm_previous'
                    );
$config['workflow/workflow_mrp_msg_model/update_batch'] = array(
                                'mrp_id' => 'wmm_mrp_id',
                                'creator' => 'wmm_creator',
                                'create_datetime' => 'wmm_create_datetime',
                                'msg' => 'wmm_msg',
                                'workflow_mrp_id' => 'wmm_workflow_mrp_id',
                        'previous' => 'wmm_previous'
                    );
