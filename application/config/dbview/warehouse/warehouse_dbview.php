<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/warehouse_model/select'] = array(
    'w_num' => array(
        'num',
        'v'
    ),
    'w_warehouse_shelve_num' => 'warehouse_shelve_num',
    'w_width' => 'width',
    'w_height' => 'height',
    'w_min' => 'min',
    'w_max' => 'max',
    'w_status' => 'status_v',
    'ws_label' => 'status_label'
);

$config['warehouse/warehouse_model/is_exist'] = array(
    'w_num' => array(
        'num',
        'v'
    )
);

$config['warehouse/warehouse_model/select_height'] = array(
    'w_num' => array(
        'num',
        'v'
    )
);
