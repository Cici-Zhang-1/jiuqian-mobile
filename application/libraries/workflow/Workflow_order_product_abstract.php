<?php namespace Wop;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
abstract class Workflow_order_product_abstract{
    //定义一个环境角色，也就是封装状态的变换引起的功能变化
    protected  $_Workflow;
    protected $_Source_id;
    protected $_Source_ids;
    protected $_CI;

    public function set_workflow(Workflow_order_product $Workflow){
        $this->_Workflow = $Workflow;
        $this->_CI = &get_instance();
    }
    /**
     * 作废订单时起作用
     */
    public function remove(){
        if (!!($OrderProductId = $this->_are_removable())) {
            $this->_Workflow->initialize($OrderProductId);
            $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['remove']);
            $this->_Workflow->store_message('订单产品作废');
            return true;
        } else {
            $this->_Workflow->set_failue('没有可以作废的订单产品!');
            return false;
        }
    }

    /**
     * 判断订单产品是否可以删除
     * @return array|bool
     */
    private function _are_removable () {
        $this->_Source_ids = $this->_Workflow->get_source_ids();
        if(!!($Ids = $this->_CI->order_product_model->are_dismantlable($this->_Source_ids))){
            $Workflow = array();
            foreach ($Ids as $key => $value){
                $Workflow[] = $value['order_product_id'];
            }
            if(!empty($Workflow)){
                return $Workflow;
            }
        }
        return false;
    }
    /**
     * 工作流向父级传递
     * @param $Method
     * @return bool
     */
    protected function _workflow_propagation ($Method) {
        if (!!($OrderId = $this->_get_order_id())) {
            $this->_CI->load->model('order/order_model');
            $W = $this->_CI->workflow->initialize('order');
            foreach ($OrderId as $Key => $Value) {
                $W->initialize($Value['order_id']);
                if ($W->{$Method}()) {
                    continue;
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
    protected function _get_order_id(){
        $this->_Source_ids = $this->_Workflow->get_source_ids();
        if(!!($Ids = $this->_CI->order_product_model->select_order_id_by_order_product_id($this->_Source_ids))){
            return $Ids;
        }
        return false;
    }

    /**
     * 执行某个已经执行过的操作
     */
    protected function _execute_record ($Name) {
        if(!!($Query = $this->_CI->workflow_order_product_model->select_by_name($Name))) {
            $this->_Workflow->store_message('执行了' . $Query['label'] . '操作');
        } else {
            $this->_Workflow->store_message('执行了一个操作');
        }
        return true;
    }

    /**
     * 送装
     * @return bool
     */
    public function post_sale(){
        $this->_Workflow->store_message('订单产品执行了送装操作');
        return true;
    }
}