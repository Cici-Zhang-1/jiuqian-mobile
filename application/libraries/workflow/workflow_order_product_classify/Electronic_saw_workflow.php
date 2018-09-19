<?php namespace Wopc;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:34
 */
class Electronic_saw_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function electronic_saw () {
        $this->_Workflow->store_message('++等待下料');
        return true;
    }

    public function re_shear () {
        return $this->_workflow_previous();
    }

    public function electronic_sawed() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['electronic_sawed'], array('saw' => $this->_CI->session->userdata('uid'), 'saw_datetime' => date('Y-m-d H:i:s')));
        return $this->_Workflow->electronic_sawed();
    }

    public function electronic_sawing() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['electronic_sawing'], array('saw' => $this->_CI->session->userdata('uid')));
        return $this->_Workflow->electronic_sawing();
    }

    public function __call($name, $arguments){
        ;
    }
}