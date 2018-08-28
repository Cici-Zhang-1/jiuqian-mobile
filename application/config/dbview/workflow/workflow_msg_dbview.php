<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['workflow/workflow_msg_model/select'] = array(
                                'wm_id' => 'v',                                        'wm_model' => 'model',                                        'wm_source_id' => 'source_id',                                        'wm_creator' => 'creator',                                        'wm_create_datetime' => 'create_datetime',                                        'wm_msg' => 'msg',                                        'wm_status' => 'status'                    );
$config['workflow/workflow_msg_model/select_by_order_product_v'] = array(
    'u_truename' => 'creator',
    'wm_create_datetime' => 'create_datetime',
    'wm_msg' => 'msg'
);

$config['data/workflow_msg_model/select_by_oid'] = array(
    'wm_id' => 'wmid',
    'wm_model' => 'model',
    'u_truename' => 'creator',
    'wm_create_datetime' => 'create_datetime',
    'wm_msg' => 'msg',
    'o_num' => 'target',
);
$config['data/workflow_msg_model/select_by_opids'] = array(
    'wm_id' => 'wmid',
    'wm_model' => 'model',
    'u_truename' => 'creator',
    'wm_create_datetime' => 'create_datetime',
    'wm_msg' => 'msg',
    'op_num' => 'target',
);
$config['data/workflow_msg_model/select_by_opcids'] = array(
    'wm_id' => 'wmid',
    'wm_model' => 'model',
    'u_truename' => 'creator',
    'wm_create_datetime' => 'create_datetime',
    'wm_msg' => 'msg',
    'op_num' => 'target',
);
