<?php namespace Wopb;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 21:24
 */
class Packing_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function ppacking () {
        $this->_Workflow->store_message('正在打包');
    }

    public function re_ppack () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['pack']);
        $this->_Workflow->re_ppack();
    }

    public function ppacked() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['packed']);
        $this->_Workflow->ppacked();
    }

    public function __call($name, $arguments) {
        ;
    }
}