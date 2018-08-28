<?php namespace Wopc;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:34
 */
class Edged_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function edged() {
        $this->_Workflow->store_message('已完成封边');
        $this->_workflow_next();
    }

    public function __call($name, $arguments) {
        ;
    }
}