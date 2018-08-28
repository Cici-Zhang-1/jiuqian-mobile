<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/speci_model/select'] = array(
                                's_id' => 'v',
    's_product_id' => 'product_id',
    'p_name' => 'product',
    's_name' => 'name',
    's_parent' => 'parent',
    's_class' => 'class',
    's_remark' => 'remark',
    'u_truename' => 'creator',
    's_create_datetime' => 'create_datetime'
);

$config['product/speci_model/is_exist'] = array(
    's_id' => 'v',
    's_class' => 'class',
    's_parent' => 'parent',
    's_name' => 'name'
);

$config['product/speci_model/is_exists'] = array(
    's_id' => 'v',
    's_class' => 'class',
    's_parent' => 'parent',
    's_name' => 'name'
);