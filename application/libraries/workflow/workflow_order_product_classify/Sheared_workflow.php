<?php namespace Wopc;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 9:33
 */
class Sheared_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function re_shear () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['shear']);
        $this->_Workflow->shear();
    }

    public function sheared(){
        $this->_Workflow->store_message('已安排生产下料');
        $this->_workflow_propagation(__FUNCTION__);
        $this->_workflow_next();
    }

    public function __call($name, $arguments){
        ;
    }
}