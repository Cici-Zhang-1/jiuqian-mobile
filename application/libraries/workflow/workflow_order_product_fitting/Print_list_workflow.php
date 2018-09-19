<?php namespace Wopf;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Print_list_workflow extends Workflow_order_product_fitting_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function print_list() {
        $this->_Workflow->store_message('订单产品配件等待打印清单');
        return true;
    }

    public function printed_list () {
        $this->_Workflow->set_data(array('print' => $this->_CI->session->userdata('uid'), 'print_datetime' => date('Y-m-d H:i:s')));
        $this->_Workflow->store_message('订单产品配件已经打印清单');
        $this->_workflow_propagation(__FUNCTION__);
        return $this->_workflow_next();
    }

    public function __call($name, $arguments){
        ;
    }
}