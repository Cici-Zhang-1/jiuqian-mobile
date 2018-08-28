<?php namespace Wopc;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:33
 */
class Punching_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function punching(){
        $this->_Workflow->store_message('正在打孔');
    }

    public function re_punch () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['punch']);
        $this->_Workflow->re_punch();
    }

    public function punched() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['punched']);
        $this->_Workflow->punched();
    }

    public function __call($name, $arguments){
        ;
    }
}