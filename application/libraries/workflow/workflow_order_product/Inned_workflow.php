<?php namespace Wop;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author Administrator
 * @version
 * @des
 * 订单产品已经入库
 */
class Inned_workflow extends Workflow_order_product_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }
    
    public function inned(){
        $this->_Workflow->store_message('该订单产品已全部入库!');
        return $this->_workflow_propagation(__FUNCTION__);
    }

    public function packed () {
        $this->_Workflow->store_message('订单产品重新打包!');
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['packed']);
        return $this->_Workflow->packed();
    }
    
    public function __call($name, $arguments){
        $this->_execute_record($name);
    }
}