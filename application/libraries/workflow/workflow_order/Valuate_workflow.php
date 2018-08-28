<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 * 等待核价
 */
class Valuate_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    /**
     * 等待核价
     */
    public function valuate () {
        $this->_Workflow->store_message('等待计价');
        return true;
    }

    /**
     * 正在计价
     */
    public function valuating () {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['valuating']);
        return $this->_Workflow->valuating();
    }
    /**
     * 确认核价
     */
    public function valuated() {
        if($this->_is_valuated()){
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['valuated']);
            return $this->_Workflow->valuated();
        } else {
            $this->_Workflow->set_failue('请先计价，然后再确认!');
            return false;
        }
    }
    /**
     * 重新拆单
     */
    public function re_dismantle(){
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['dismantling']);
        return $this->_Workflow->re_dismantle();
    }

    public function __call($name, $arguments){
        ;
    }
}