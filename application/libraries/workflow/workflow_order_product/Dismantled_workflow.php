<?php namespace Wop;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月14日
 * @author Administrator
 * @version
 * @des
 */
class Dismantled_workflow extends \Wop\Workflow_order_product_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function dismantled() {
        $this->_Workflow->set_data(array('dismantle' => $this->_CI->session->userdata('uid'), 'dismantle_datetime' => date('Y-m-d H:i:s')));
        $this->_Workflow->store_message('已拆订单产品');
        return $this->_workflow_propagation(__FUNCTION__);
    }

    /**
     * 重新拆单
     */
    public function re_dismantle() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['dismantling']);
        return $this->_Workflow->re_dismantle();
    }

    /**
     * 优化
     */
    public function optimize(){
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['pre_produce']);
        return $this->_Workflow->pre_produce();
    }

    /**
     * 打印清单
     */
    public function printed_list() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['pre_produce']);
        return $this->_Workflow->pre_produce();
    }

    /**
     * 订单只有部分打包了
     */
    public function packing () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['packing']);
        return $this->_Workflow->packing();
    }
    /**
     * 已经打包的状态转换为已经打包
     */
    public function packed() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['packed']);
        return $this->_Workflow->packed();
    }

    /**
     * 不优化时转换
     * @return bool
     */
    public function to_board () {
        $this->_Source_ids = $this->_Workflow->get_source_ids();
        require_once dirname(__FILE__) . '/To.php';
        $To = new \Wop\To($this->_Source_ids);
        if ($To->to_board()) {
            $this->_Workflow->store_message('订单转换到推台锯');
            return true;
        } else {
            $this->_Workflow->set_failue($To->get_error_msg());
            return false;
        }
    }

    public function to_classify () {
        $this->_Source_ids = $this->_Workflow->get_source_ids();
        require_once dirname(__FILE__) . '/To.php';
        $To = new \Wop\To($this->_Source_ids);
        if ($To->to_classify()) {
            $this->_Workflow->store_message('订单转换到电子锯');
            return true;
        } else {
            $this->_Workflow->set_failue($To->get_error_msg());
            return false;
        }
    }

    public function __call($name, $arguments){
        ;
    }
}