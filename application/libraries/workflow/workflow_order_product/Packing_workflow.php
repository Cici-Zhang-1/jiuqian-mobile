<?php namespace Wop;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author Administrator
 * @version
 * @des
 * 订单产品正在包，有留包
 */
class Packing_workflow extends Workflow_order_product_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function packing() {
        $this->_Workflow->set_edit_data();
        $this->_Workflow->store_message('订单产品正在打包, 部分板块已经完成打包!');
        return true;
    }

    public function packed() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['packed']);
        return $this->_Workflow->packed();
    }

    public function __call($name, $arguments){
        return $this->_execute_record($name);
    }
}