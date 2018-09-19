<?php namespace Wopb;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:34
 */
class Punching_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function punching() {
        $this->_Workflow->store_message('已安排正在打孔');
        return true;
    }

    public function re_punch() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['punch'], array('punch' => ZERO, 'punch_datetime' => null));
        return $this->_Workflow->re_punch();
    }

    public function punched() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['punched'], array('punch' => $this->_CI->session->userdata('uid'), 'punch_datetime' => date('Y-m-d H:i:s')));
        return $this->_Workflow->punched();
    }

    public function __call($name, $arguments) {
        ;
    }
}