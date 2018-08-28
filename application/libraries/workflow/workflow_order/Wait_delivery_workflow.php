<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Administrator
 * @version
 * @des
 */
class Wait_delivery_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function wait_delivery () {
        $this->_Workflow->store_message('订单等待发货!');
        return true;
    }

    public function delivering () {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['delivering']);
        return $this->_Workflow->delivering();
    }
    public function delivered() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['delivered']);
        return $this->_Workflow->delivered();
    }

    public function re_delivery () {
        $this->_Workflow->store_message('重新安排发货!');
        return true;
    }

    private function _money_logistics() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['money_logistics']);
        return $this->_Workflow->money_logistics();
    }

    private function _blank_note() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['blank_note']);
        return $this->_Workflow->blank_note();
    }

    /**
     * 订单等待发货之后，重新入库
     * @return mixed
     */
    public function inned () {
        $this->_Workflow->store_message('订单重新入库!');
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['inned']);
        return $this->_Workflow->inned();
    }

    public function __call($name, $arguments){
        ;
    }
}