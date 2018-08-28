<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 * 已经核价
 */
class Valuated_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function valuated(){
        $this->_Workflow->set_datetime(array('valuate' => $this->_CI->session->userdata('uid'), 'valuate_datetime' => date('Y-m-d H:i:s')));
        $this->_Workflow->store_message('已经完成计价');

        if ($this->_is_over_min_check_sum()) {
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['check']);
            $this->_Workflow->check();
        } else {
            $this->_Workflow->store_message('未达到核价最低金额, 直接通过财务审核');
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['checked']);
            $this->_Workflow->checked();
        }
    }

    /**
     * 判断是否超出最低核价金额
     */
    private function _is_over_min_check_sum () {
        $this->_CI->load->model('data/configs_model');
        $MinCheckSum = $this->_CI->configs_model->select_by_name('min_check_sum');
        $Query = $this->_CI->order_model->select_detail(array('order_id' => $this->_Source_id));

        if ($Query['content']['sum'] > $MinCheckSum) {
            return true;
        }
        return false;
    }

    public function __call($name, $arguments){
        ;
    }
}