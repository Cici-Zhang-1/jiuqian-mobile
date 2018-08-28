<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 * 正在核价
 */
class Valuating_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function valuating(){
        $this->_Workflow->store_message('正在计价');
        return true;
    }

    public function valuated(){
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
    public function re_dismantle() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['dismantling']);
        return $this->_Workflow->re_dismantle();
    }

    public function re_valuate () {
        $this->_Workflow->set_datetime(array(
            'valuate' => ZERO,
            'valuate_datetime' => null,
            'check' => ZERO,
            'check_remark' => null,
            'check_datetime' => null,
            'sure' => ZERO,
            'sure_datetime' => null));
        $this->_Workflow->store_message('重新核价');
    }

    public function __call($name, $arguments){
        ;
    }
}