<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 * 拆单完成
 */
class Dismantled_workflow extends Workflow_order_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    /**
     * 拆单完成后直接过度到等待核价状态
     */
    public function dismantled() {
        if ($this->_is_dismantled()) {
            $this->_Workflow->set_datetime(array('dismantle' => $this->_CI->session->userdata('uid'), 'dismantle_datetime' => date('Y-m-d H:i:s')));
            $this->_Workflow->store_message('完成拆单');
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['valuate']);
            return $this->_Workflow->valuate();
        } else {
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['dismantling']);
            return $this->_Workflow->dismantling();
        }
    }

    /**
     * 判断订单是否已经拆单
     * @return mixed
     */
    private function _is_dismantled () {
        $this->_CI->load->model('order/order_product_model');
        return $this->_CI->order_product_model->are_dismantled($this->_Source_id);
    }

    public function __call($name, $arguments){
        ;
    }
}