<?php namespace Wopc;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Print_list_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function print_list() {
        $this->_Workflow->store_message('订单产品分类等待打印清单');
        return true;
    }

    public function printed_list () {
        $this->_Workflow->set_data(array('print' => $this->_CI->session->userdata('uid'), 'print_datetime' => date('Y-m-d H:i:s')));
        $this->_Workflow->store_message('订单产品分类已经打印清单');
        if ($this->_workflow_propagation(__FUNCTION__)) {
            return $this->_workflow_next();
        }
        return false;
    }

    public function __call($name, $arguments){
        $Methods = array('optimize');
        if (in_array($name, $Methods)) {
            return true;
        }
    }
}