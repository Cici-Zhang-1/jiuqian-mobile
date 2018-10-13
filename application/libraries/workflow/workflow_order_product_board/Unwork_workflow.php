<?php namespace Wopb;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Unwork_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function __call($name, $arguments){
        ;
    }
}