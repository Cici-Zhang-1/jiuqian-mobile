<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['activity/activity_model/select'] = array(
                                'a_id' => 'v',
    'a_title' => 'title',
    'a_num' => 'num',
    'a_image' => 'image',
    'a_content' => 'content',
    'a_status' => 'status',
    'a_notice' => 'notice',
    'A.bt_label' => 'status_label',
    'B.bt_label' => 'notice_label',
    'u_truename' => 'creator',
    'a_create_datetime' => 'create_datetime'
);

$config['activity/activity_model/select_notice'] = array(
    'a_id' => 'v',
    'a_title' => 'title',
    'a_num' => 'num',
    'a_image' => 'image',
    'a_content' => 'content',
    'a_status' => 'status',
    'a_notice' => 'notice',
    'a_creator' => 'creator',
    'a_create_datetime' => 'create_datetime'
);

$config['activity/activity_model/select_image'] = array(
    'a_id' => 'v',
    'a_title' => 'title',
    'a_num' => 'num',
    'a_image' => 'image',
    'a_content' => 'content',
    'a_status' => 'status',
    'a_notice' => 'notice',
    'a_creator' => 'creator',
    'a_create_datetime' => 'create_datetime'
);
