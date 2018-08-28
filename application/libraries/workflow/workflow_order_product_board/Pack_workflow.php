<?php namespace Wopb;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:33
 */
class Pack_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function ppack(){
        $this->_Workflow->store_message('==等待包装打包');
    }

    public function re_ppack () {
        $this->_Workflow->store_message('岗位工作变动，重新安排打包工人!');
    }

    public function ppacking () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['packing']);
        $this->_Workflow->ppacking();
    }

    public function ppacked() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['packed']);
        $this->_Workflow->ppacked();
    }

    public function __call($name, $arguments){
        ;
    }
}