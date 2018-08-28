<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/application_model/select'] = array(
                                'a_id' => 'v',
    'a_type' => 'type',
    'a_source' => 'source',
    'a_des' => 'des',
    'a_remark' => 'remark',
    'a_status' => 'status',
    'as_label' => 'status_label',
    'a_create_datetime' => 'create_datetime',
    'a_reply_datetime' => 'reply_datetime',
    'o_id' => 'order_id',
    'o_num' => 'num',
    'C.u_truename' => 'creator',
    'R.u_truename' => 'replyer'
);
