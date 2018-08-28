<?php namespace Wop;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author Zhangcc
 * @version
 * @des
 * 订单产品正在生产
 */
class Producing_workflow extends Workflow_order_product_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function producing () {
        $this->_Workflow->store_message('订单产品正在进行生产');
        $this->_workflow_propagation(__FUNCTION__);
    }

    public function packing () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['packing']);
        return $this->_Workflow->packing();
    }

    public function packed(){
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['packed']);
        return $this->_Workflow->packed();
    }

    public function __call($name, $arguments){
        ;
    }
}