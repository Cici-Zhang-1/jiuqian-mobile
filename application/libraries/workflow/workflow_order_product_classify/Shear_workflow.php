<?php namespace Wopc;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 9:33
 */
class Shear_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function shear(){
        $this->_Workflow->store_message('等待排产');
        $this->_workflow_propagation(__FUNCTION__);
    }

    public function re_shear () {
        $this->_Workflow->store_message('岗位变动或安排出错，重新安排下料工人!');
        // $this->_workflow_order_product(__FUNCTION__); // 因为可能已经排产了之后重新安排下料，所以会有状态回退
    }

    public function shearing() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['shearing']);
        $this->_Workflow->shearing();
    }

    public function sheared() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['sheared']);
        $this->_Workflow->sheared();
    }

    public function __call($name, $arguments){
        ;
    }
}