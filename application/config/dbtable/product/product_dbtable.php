<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['product/product_model'] = array(
                                'name' => 'p_name',
                                'parent' => 'p_parent',
                                'parents' => 'p_parents',
                                'class' => 'p_class',
                                'code' => 'p_code',
                                'remark' => 'p_remark',
                        'production_line' => 'p_production_line',
                        'delete' => 'p_delete'
                    );
$config['product/product_model/insert'] = array(
                                'name' => 'p_name',
                                'parent' => 'p_parent',
                                'parents' => 'p_parents',
                                'class' => 'p_class',
                                'code' => 'p_code',
                                'remark' => 'p_remark',
    'production_line' => 'p_production_line',
                        'delete' => 'p_delete'
                    );
$config['product/product_model/insert_batch'] = array(
                                'name' => 'p_name',
                                'parent' => 'p_parent',
                                'parents' => 'p_parents',
                                'class' => 'p_class',
                                'code' => 'p_code',
    'production_line' => 'p_production_line',
                                'remark' => 'p_remark',
                        'delete' => 'p_delete'
                    );
$config['product/product_model/update'] = array(
                                'name' => 'p_name',
                                'parent' => 'p_parent',
                                'parents' => 'p_parents',
                                'class' => 'p_class',
                                'code' => 'p_code',
    'production_line' => 'p_production_line',
                                'remark' => 'p_remark',
                        'delete' => 'p_delete'
                    );
$config['product/product_model/update_batch'] = array(
                                'name' => 'p_name',
                                'parent' => 'p_parent',
                                'parents' => 'p_parents',
                                'class' => 'p_class',
                                'code' => 'p_code',
    'production_line' => 'p_production_line',
                                'remark' => 'p_remark',
                                'delete' => 'p_delete',
                        'v' => 'p_id'
                    );
