<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author Administrator
 * @version
 * @des
 * 正在生产
 */
class Producing_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function producing () {
        $this->_Workflow->store_message('订单正在生产...');
        return true;
    }

    public function inned() {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['inned']);
        return $this->_Workflow->inned();
    }

    public function __call($name, $arguments){
        return true;
    }
}