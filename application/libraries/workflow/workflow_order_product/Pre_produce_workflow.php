<?php namespace Wop;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Pre_produce_workflow extends Workflow_order_product_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function pre_produce () {
        $this->_Workflow->store_message('订单产品正在进行生产准备');
        return $this->_workflow_propagation(__FUNCTION__);
    }

    /**
     * 已经确认下料的状态转换为正在生产
     */
    public function electronic_sawed () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['producing'], array(
            'producing' => $this->_CI->session->userdata('uid'),
            'producing_datetime' => date('Y-m-d H:i:s')
        ));
        return $this->_Workflow->producing();
    }

    /**
     * 确认封边的状态转换为正在生产
     */
    public function edged () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['producing'], array(
            'producing' => $this->_CI->session->userdata('uid'),
            'producing_datetime' => date('Y-m-d H:i:s')
        ));
        return $this->_Workflow->producing();
    }

    /**
     * 确认打孔的状态转换为正在生产
     */
    public function punched () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['producing'], array(
            'producing' => $this->_CI->session->userdata('uid'),
            'producing_datetime' => date('Y-m-d H:i:s')
        ));
        return $this->_Workflow->producing();
    }

    /**
     * 确认扫描的状态转换为正在生产
     */
    public function scanned () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['producing'], array(
            'producing' => $this->_CI->session->userdata('uid'),
            'producing_datetime' => date('Y-m-d H:i:s')
        ));
        return $this->_Workflow->producing();
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
    /**
     * 转换到优化
     * @return bool
     */
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
        $Methods = array('optimize', 'printed_list');
        if (in_array($name, $Methods)) {
            return true;
        }
    }
}