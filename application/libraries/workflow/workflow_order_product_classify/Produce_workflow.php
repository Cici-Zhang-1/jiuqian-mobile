<?php namespace Wopc;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:33
 */
class Produce_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function electronic_sawed(){
        $this->_Workflow->store_message('已下料或...');
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['edge']);
        $this->_Workflow->edge();
    }
    public function edge () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['edge']);
        $this->_Workflow->edge();
    }
    public function sscan () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['ScanPlate']);
        $this->_Workflow->sscan();
    }
    public function ppack () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['pack']);
        $this->_Workflow->ppack();
    }

    public function __call($name, $arguments){
        ;
    }
}