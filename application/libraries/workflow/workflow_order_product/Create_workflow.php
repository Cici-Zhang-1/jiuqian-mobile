<?php namespace Wop;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Create_workflow extends \Wop\Workflow_order_product_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }
    
    public function create(){
        $this->_Workflow->store_message('新建订单产品');
        return true;
    }
    
    public function dismantling(){
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['dismantling']);
        return $this->_Workflow->dismantling();
    }
    
    public function dismantled(){
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['dismantled']);
        return $this->_Workflow->dismantled();
    }

    public function init_post_sale () {
        $this->_Workflow->store_message('初始化送装');
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['dismantled']);
        return true;
    }
    public function __call($name, $arguments){
        ;
    }
}