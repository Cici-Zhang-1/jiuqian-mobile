<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/pick_sheet/re_delivery'] = array(
    array (
        'field' => 'v[]',
        'label' => '发货单号',
        'rules' => 'trim|required|numeric|min_length[1]|max_length[10]'
    )
);
