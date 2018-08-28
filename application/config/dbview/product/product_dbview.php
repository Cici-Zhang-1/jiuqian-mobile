<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/product_model/select'] = array(
                                'p_id' => 'v',
    'p_name' => 'name',
    'p_parent' => 'parent',
    'p_class' => 'class',
    'p_code' => 'code',
    'p_production_line' => 'production_line',
    'p_remark' => 'remark',
    'p_delete' => 'delete',
    'pl_num' => 'production_line_num'
);
$config['product/product_model/select_product_code_by_id'] = array(
    'p_id' => 'v',
    'p_code' => 'code',
    'p_name' => 'name',
    'p_production_line' => 'production_line',
    'pl_num' => 'production_line_num'
);
$config['product/product_model/select_by_code'] = array(
    'p_id' => 'v',
    'p_code' => 'code',
    'p_name' => 'name',
    'p_production_line' => 'production_line',
    'pl_num' => 'production_line_num'
);

$config['product/product_model/is_exist'] = array(
    'p_id' => 'v',
    'p_class' => 'class',
    'p_parents' => 'parents',
    'p_code' => 'code',
    'p_name' => 'name',
    'p_production_line' => 'production_line',
    'pl_num' => 'production_line_num'
);
