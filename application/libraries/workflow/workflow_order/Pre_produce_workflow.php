<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 */
class Pre_produce_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }
    /**
     * 生产预处理
     * @param unknown $Type
     */
    public function pre_produce() {
        $this->_Workflow->store_message('正在做生产准备工作: 优化或者打印清单');
        return true;
    }

    /**
     * 开始生产
     */
    public function producing(){
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['producing']);
        return $this->_Workflow->producing();
    }

    public function inned () {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['inned']);
        return $this->_Workflow->inned();
    }

    public function __call($name, $arguments){
        ;
    }
}