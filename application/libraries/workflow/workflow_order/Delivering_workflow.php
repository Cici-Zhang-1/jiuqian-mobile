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
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function delivering () {
        $this->_Workflow->set_edit_data();
        $this->_Workflow->store_message('已经安排这个订单先发部分货!');
        return true;
    }

    public function delivered() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['delivered']);
        return $this->_Workflow->delivered();
    }

    public function payed() {
        $this->_Workflow->store_message('已经支付这个订单!');
        return true;
    }

    public function inned() {
        $this->_Workflow->set_edit_data();
        $this->_Workflow->store_message('订单修改了入库件数!');
        return true;
    }

    public function re_delivery () {
        $this->_Workflow->store_message('重新安排发货!');
        return true;
    }

    public function __call($name, $arguments){
        ;
    }
}