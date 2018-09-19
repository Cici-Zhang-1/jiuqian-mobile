<?php namespace Wopb;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:34
 */
class Electronic_sawed_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function electronic_sawed () {
        $this->_Workflow->store_message('已经完成下料');
        $this->_workflow_propagation(__FUNCTION__);
        return $this->_workflow_next();
    }

    public function __call($name, $arguments){
        ;
    }
}
