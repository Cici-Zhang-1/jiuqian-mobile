<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月24日
 * @author Administrator
 * @version
 * @des
 */
class Blank_note_workflow extends Workflow_order_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function blank_note () {
        $this->_Workflow->store_message('订单已经安排发货, 发货白条!');
        return true;
    }

    public function payed() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['outed']);
        $this->_Workflow->store_message('订单已支付!');
        return $this->_Workflow->outed();
    }

    public function re_delivery(){
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['wait_delivery']);
        return $this->_Workflow->re_delivery();
    }

    public function __call($name, $arguments){
        ;
    }
}