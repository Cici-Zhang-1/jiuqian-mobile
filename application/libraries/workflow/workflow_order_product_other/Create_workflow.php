<?php namespace Wopo;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Create_workflow extends Workflow_order_product_other_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }
    
    public function create() {
        $this->_Workflow->store_message('新建订单产品外购');
        return true;
    }

    public function print_list() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_other::$AllWorkflow['print_list']);
        return $this->_Workflow->print_list();
    }

    public function __call($name, $arguments){
        ;
    }
}