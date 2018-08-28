<?php namespace Wopb;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
abstract class Workflow_order_product_board_abstract{
    //定义一个环境角色，也就是封装状态的变换引起的功能变化
    protected  $_Workflow;
    protected $_Source_id;
    protected $_Source_ids;
    protected $_CI;

    public function set_workflow(Workflow_order_product_classify $Workflow){
        $this->_Workflow = $Workflow;
        $this->_CI = &get_instance();
    }

    protected function _workflow_propagation ($Method) {
        if(!!($OrderProductId = $this->_get_order_product_id())){
            $this->_CI->load->model('order/order_product_model');
            $W = $this->_CI->workflow->initialize('order_product');
            foreach ($OrderProductId as $Key => $Value) {
                if ($W->initialize($Value['order_product_id'])) {
                    $W->{$Method}();
                } else {
                    $this->_Workflow->set_failue($W->get_failue());
                    return false;
                }
            }
            return true;
        }
        return false;
    }
    /**
     * 获取所有已经下料的订单产品V
     */
    protected function _get_order_product_id(){
        $this->_Source_ids = $this->_Workflow->get_source_ids();
        if(!!($Ids = $this->_CI->order_product_classify_model->select_order_product_id($this->_Source_ids))){
            return $Ids;
        }
        return false;
    }

    /**
     * 当一个工序的状态执行完成后，获取下一工序的初始状态
     * @return bool
     */
    protected function _workflow_next () {
        $this->_Source_ids = $this->_Workflow->get_source_ids();
        if(!!($WorkflowNext = $this->_CI->order_product_board_model->select_workflow_next($this->_Source_ids))){
            foreach ($WorkflowNext as $Key => $Value) {
                $this->_Workflow->initialize($Value['v']); // 初始化v
                $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow[$Value['workflow_procedure_name']], array('procedure' => $Value['procedure']));
                $this->_Workflow->{$Value['workflow_procedure_name']}();
            }
        }
        return true;
    }

    /**
     * 执行某个已经执行过的操作
     */
    protected function _execute_record ($Name) {
        if(!!($Query = $this->_CI->workflow_procedure_model->select_by_name($Name))) {
            $this->_Workflow->store_message('执行了' . $Query['label'] . '操作');
        } else {
            $this->_Workflow->store_message('执行了无效的操作');
        }
    }
}