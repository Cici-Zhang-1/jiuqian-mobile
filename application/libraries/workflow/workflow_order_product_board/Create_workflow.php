<?php namespace Wopb;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Create_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }
    
    public function create() {
        $this->_Workflow->store_message('新建订单产品板材');
    }

    public function print_list() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['print_list']);
        $this->_Workflow->print_list();
    }

    public function __call($name, $arguments){
        ;
    }
}