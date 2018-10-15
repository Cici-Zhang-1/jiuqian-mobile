<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['order/dismantle/disabled'] = array(
    array(
        'field' => 'v[]',
        'label' => '订单产品编号',
        'rules' => 'required|numeric|min_length[1]|max_length[10]'
    )
);
