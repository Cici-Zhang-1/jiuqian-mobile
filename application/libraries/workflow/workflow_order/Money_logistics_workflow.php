<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Zhangcc
 * @version
 * @des
 * 物流代收
 */
class Money_logistics_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function money_logistics () {
        $this->_Workflow->store_message('订单已经安排发货, 物流代收!');
    }

    public function payed(){
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['outed']);
        $this->_Workflow->store_message('订单已支付!');
        $this->_Workflow->outed();
    }
    
    public function re_delivery(){
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['wait_delivery']);
        $this->_Workflow->re_delivery();
    }
    
    public function __call($name, $arguments){
        ;
    }
}