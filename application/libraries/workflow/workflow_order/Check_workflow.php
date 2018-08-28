<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 * 等待核价
 */
class Check_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    /**
     * 等待核价
     */
    public function check () {
        $this->_Workflow->store_message('等待财务核价');
        return true;
    }

    /**
     * 确认核价
     */
    public function checked() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['checked']);
        return $this->_Workflow->checked();
    }

    /**
     * 重新计价
     */
    public function re_valuate () {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['valuating']);
        return $this->_Workflow->re_valuate();
    }
    /**
     * 重新拆单
     */
    public function re_dismantle() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['dismantling']);
        return $this->_Workflow->re_dismantle();
    }

    public function __call($name, $arguments){
        ;
    }
}