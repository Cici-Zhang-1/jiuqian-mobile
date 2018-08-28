<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Zhangcc
 * @version
 * @des
 * 款到发货
 */
class Delivering_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function delivering () {
        $this->_Workflow->store_message('已经安排这个订单先发部分货!');
    }

    public function delivered() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['delivered']);
        $this->_Workflow->delivered();
    }

    private function _money_logistics() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['money_logistics']);
        $this->_Workflow->money_logistics();
    }

    private function _blank_note() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['blank_note']);
        $this->_Workflow->blank_note();
    }

    public function payed() {
        $this->_Workflow->store_message('已经支付这个订单!');
    }

    public function inned() {
        $Pack = $this->_get_packed();
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['delivering'], $Pack);
        $this->_Workflow->store_message('订单修改了入库件数!');
    }

    public function __call($name, $arguments){
        ;
    }
}