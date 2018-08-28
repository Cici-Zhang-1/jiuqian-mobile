<?php namespace Wopb;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:33
 */
class Scanned_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function sscanned() {
        $this->_Workflow->store_message('==已扫描');
        $this->_workflow_next();
    }

    public function __call($name, $arguments){
        ;
    }
}