<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['warehouse/pick_scan/add'] = array(
                        array (
            'field' => 'order_product_num[]',
            'label' => '订单产品编号',
            'rules' => 'trim|required|max_length[64]'
        ),
                                array (
            'field' => 'stock_outted_v',
            'label' => '拣货单V',
            'rules' => 'trim|required|numeric|max_length[10]'
        )
            );
