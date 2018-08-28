<?php namespace Wm;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
abstract class Workflow_mrp_abstract{
    //定义一个环境角色，也就是封装状态的变换引起的功能变化
    protected  $_Workflow;
    protected $_Source_id;
    protected $_Source_ids;
    protected $_CI;

    public function set_workflow(Workflow_mrp $Workflow){
        $this->_Workflow = $Workflow;
        $this->_CI = &get_instance();
    }
    /**
     * 废除MRP时起作用
     */
    public function remove(){
        $this->_Workflow->edit_current_workflow(Workflow_mrp::$AllWorkflow['remove']);
        $this->_Workflow->remove();
    }

    protected function _workflow_propagation ($Method) {
        if (!!($OrderProductClassifyId = $this->_get_order_product_classify_id())) {
            $this->_CI->load->model('order/order_product_classify_model');
            $W = $this->_CI->workflow->initialize('order_product_classify');
            foreach ($OrderProductClassifyId as $Key => $Value) {
                if ($W->initialize($Value['order_product_classify_id'])) {
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
    protected function _get_order_product_classify_id(){
        $this->_Source_ids = $this->_Workflow->get_source_ids();
        if(!!($Ids = $this->_CI->mrp_model->select_order_product_classify_id($this->_Source_ids))){
            return $Ids;
        }
        return false;
    }
}