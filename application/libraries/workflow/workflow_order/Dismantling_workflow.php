<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 * 正在拆单
 */
class Dismantling_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function dismantling() {
        $this->_Workflow->store_message('正在订单');
        return true;
    }

    public function dismantled() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['dismantled']);
        return $this->_Workflow->dismantled();
    }

    /**
     * 重新拆单
     */
    public function re_dismantle() {
        $this->_Workflow->set_datetime(array('dismantle' => ZERO,
            'dismantle_datetime' => null,
            'valuate' => ZERO,
            'valuate_datetime' => null,
            'check' => ZERO,
            'check_datetime' => null,
            'sure' => ZERO,
            'sure_datetime' => null));
        $this->_Workflow->store_message('重新订单');
        return true;
    }

    public function __call($name, $arguments){
        ;
    }
}