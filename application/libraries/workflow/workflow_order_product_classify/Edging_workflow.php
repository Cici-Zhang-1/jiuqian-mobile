<?php namespace Wopc;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:34
 */
class Edging_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function edging () {
        $this->_Workflow->store_message('已安排正在封边');
    }

    public function re_edge () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['edge']);
        $this->_Workflow->re_edge();
    }

    public function edged() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['edged']);
        $this->_Workflow->edged();
    }

    public function __call($name, $arguments) {
        ;
    }
}