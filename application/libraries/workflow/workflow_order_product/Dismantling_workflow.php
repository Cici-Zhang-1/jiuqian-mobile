<?php namespace Wop;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Zhangcc
 * @version
 * @des
 */
class Dismantling_workflow extends Workflow_order_product_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function dismantling(){
        $this->_Workflow->store_message('正在拆订单产品');
        $this->_workflow_propagation(__FUNCTION__);
    }

    public function dismantled(){
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['dismantled']);
        $this->_Workflow->dismantled();
    }

    public function re_dismantle () {
        $this->_Workflow->set_data(array('dismantle' => ZERO,
            'dismantle_datetime' => null));
        $this->_Workflow->store_message('重新拆订单产品');
    }
    
    public function __call($name, $arguments){
        $this->_execute_record($name);
    }
}