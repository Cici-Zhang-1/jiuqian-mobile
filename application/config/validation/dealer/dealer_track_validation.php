<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['dealer/dealer_track/add'] = array(
                        array (
            'field' => 'shop_id',
            'label' => '店面',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'track_title',
            'label' => '跟踪主题',
            'rules' => 'trim|required|max_length[128]'
        ),
                                array (
            'field' => 'dealer_id',
            'label' => '经销商',
            'rules' => 'trim|required|numeric|max_length[10]'
        ),
                                array (
            'field' => 'track',
            'label' => '跟踪',
            'rules' => 'trim|required|max_length[65535]'
        )
            );
