<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Create_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }
    
    public function create() {
        $this->_Workflow->store_message('新建订单');
        return true;
    }
    
    public function dismantling() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['dismantling']);
        return $this->_Workflow->dismantling();
    }
    
    public function dismantled() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['dismantled']);
        return $this->_Workflow->dismantled();
    }

    public function __call($name, $arguments){
        ;
    }
}