<?php namespace Wopb;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:33
 */
class Punched_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
        $this->_CI = &get_instance();
    }

    public function punched() {
        $this->_Workflow->store_message('已完成打孔');
        if ($this->_workflow_propagation(__FUNCTION__)) {
            return $this->_workflow_next();
        }
        return false;
    }

    public function __call($name, $arguments){
        ;
    }
}