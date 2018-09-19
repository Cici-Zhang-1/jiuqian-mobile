<?php namespace Wopc;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:33
 */
class Punch_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function punch(){
        $this->_Workflow->store_message('等待打孔');
        return true;
    }

    public function re_punch () {
        $this->_Workflow->store_message('岗位工作变动，重新安排打孔工人!');
        return true;
    }

    public function punching() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['punching'], array('punch' => $this->_CI->session->userdata('uid')));
        return $this->_Workflow->punching();
    }

    public function punched() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['punched'], array('punch' => $this->_CI->session->userdata('uid'), 'punch_datetime' => date('Y-m-d H:i:s')));
        return $this->_Workflow->punched();
    }

    public function __call($name, $arguments){
        ;
    }
}