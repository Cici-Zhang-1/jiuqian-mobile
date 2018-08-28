<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 * 已经核价
 */
class Checked_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function checked(){
        $this->_Workflow->set_datetime(array('check' => $this->_CI->session->userdata('uid'), 'check_datetime' => date('Y-m-d H:i:s')));
        $this->_Workflow->store_message('财务完成核价');
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['wait_sure']);
        return $this->_Workflow->wait_sure();
    }

    public function __call($name, $arguments){
        ;
    }
}