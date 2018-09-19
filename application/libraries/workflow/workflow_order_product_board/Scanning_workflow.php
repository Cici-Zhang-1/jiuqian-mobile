<?php namespace Wopb;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13
 * Time: 21:24
 */
class Scanning_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id) {
        $this->_Source_id = $Source_id;
    }

    public function scanning () {
        $this->_Workflow->store_message('已安排正在扫描');
    }

    public function re_scan () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['scan'], array('scan' => ZERO, 'scan_datetime' => null));
        $this->_Workflow->re_scan();
    }

    public function scanned() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['scanned'], array('scan' => $this->_CI->session->userdata('uid'), 'scan_datetime' => date('Y-m-d H:i:s')));
        $this->_Workflow->scanned();
    }

    public function __call($name, $arguments) {
        ;
    }
}