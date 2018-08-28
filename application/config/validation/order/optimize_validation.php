<?php defined('BASEPATH') OR exit('No direct script access allowed');
$config['order/optimize/pre_download'] = array(
    array(
        'field' => 'v[]',
        'label' => '订单编号',
        'rules' => 'trim|intval|required|numeric|min_length[1]|max_length[10]'
    )
);
$config['order/optimize/download'] = array(
    array(
        'field' => 'v[]',
        'label' => '订单编号',
        'rules' => 'trim|intval|required|numeric|min_length[1]|max_length[10]'
    )
);
