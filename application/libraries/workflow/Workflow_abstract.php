<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
abstract class Workflow_abstract{

    //定义一个环境角色，也就是封装状态的变换引起的功能变化
    protected  $_Workflow;

    public function set_workflow(Workflow $Workflow){
        $this->_Workflow = $Workflow;
    }

    /**
     * 废除订单时起作用
     */
    public function remove(){
        $this->_Workflow->edit_current_workflow(Workflow::$AllWorkflow['order']['remove']);
        $this->_Workflow->store_message('订单作废');
    }
    
    public function remove_order_product(){
        $this->_Workflow->edit_current_workflow(Workflow::$AllWorkflow['order_product']['remove']);
        $this->_Workflow->store_message('订单产品作废');
    }
}