<?php namespace Wopb;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:34
 */
class Electronic_saw_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function electronic_saw () {
        $this->_Workflow->store_message('等待下料');
        $this->_workflow_order_product(__FUNCTION__);
    }

    public function electronic_sawed() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['electronic_sawed']);
        $this->_Workflow->electronic_sawed();
    }

    public function electronic_sawing() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['electronic_sawing']);
        $this->_Workflow->electronic_sawing();
    }

    public function __call($name, $arguments){
        ;
    }
}