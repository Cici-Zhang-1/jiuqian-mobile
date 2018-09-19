<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Zhangcc
 * @version
 * @des
 * 已发货
 */
class Delivered_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function delivered () {
        $this->_Workflow->set_datetime(array('delivery' => $this->_CI->session->userdata('uid'), 'delivery_datetime' => date('Y-m-d H:i:s')));
        $this->_Workflow->store_message('订单已经安排发货!');
        return true;
    }

    public function outed() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['outed']);
        return $this->_Workflow->outed();
    }
    
    public function re_delivery() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['delivering']);
        return $this->_Workflow->re_delivery();
    }

    public function __call($name, $arguments){
        ;
    }
}