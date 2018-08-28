<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/valuate/valuated'] = array(
    array(
        'field' => 'v[]',
        'label' => '订单编号',
        'rules' => 'required|numeric|min_length[1]|max_length[10]'
    )
);

$config['order/valuate/re_valuate'] = array(
    array(
        'field' => 'v',
        'label' => '订单编号',
        'rules' => 'required|numeric|min_length[1]|max_length[10]'
    )
);
