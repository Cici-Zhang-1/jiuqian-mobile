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

    public function sscanning () {
        $this->_Workflow->store_message('已安排正在扫描');
    }

    public function re_sscan () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['ScanPlate']);
        $this->_Workflow->re_sscan();
    }

    public function sscanned() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['scanned']);
        $this->_Workflow->sscanned();
    }

    public function __call($name, $arguments) {
        ;
    }
}