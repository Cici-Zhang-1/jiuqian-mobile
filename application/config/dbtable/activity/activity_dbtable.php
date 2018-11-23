<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['activity/activity_model'] = array(
                                'title' => 'a_title',
                                'image' => 'a_image',
                                'content' => 'a_content',
                                'status' => 'a_status',
                                'notice' => 'a_notice',
                                'creator' => 'a_creator',
                        'create_datetime' => 'a_create_datetime'
                    );
$config['activity/activity_model/insert'] = array(
                                'title' => 'a_title',
                                'content' => 'a_content',
                                'status' => 'a_status',
    'notice' => 'a_notice',
                                'creator' => 'a_creator',
                        'create_datetime' => 'a_create_datetime'
                    );
$config['activity/activity_model/insert_batch'] = array(
                                'title' => 'a_title',
                                'content' => 'a_content',
                                'status' => 'a_status',
    'notice' => 'a_notice',
                                'creator' => 'a_creator',
                        'create_datetime' => 'a_create_datetime'
                    );
$config['activity/activity_model/update'] = array(
                                'title' => 'a_title',
                                'content' => 'a_content',
    'notice' => 'a_notice',
                        'status' => 'a_status'
                    );
$config['activity/activity_model/update_batch'] = array(
                                'title' => 'a_title',
                                'content' => 'a_content',
                                'status' => 'a_status',
    'notice' => 'a_notice',
                        'v' => 'a_id'
                    );
