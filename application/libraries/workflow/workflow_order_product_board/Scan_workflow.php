<?php namespace Wopb;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:33
 */
class Scan_workflow extends Workflow_order_product_board_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function sscan(){
        $this->_Workflow->store_message('==等待做卫生和扫描');
    }

    public function re_sscan () {
        $this->_Workflow->store_message('岗位工作变动，重新安排扫描工人!');
    }

    public function sscanning () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['scanning']);
        $this->_Workflow->sscanning();
    }
    public function sscanned() {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_board::$AllWorkflow['scanned']);
        $this->_Workflow->sscanned();
    }

    public function __call($name, $arguments){
        ;
    }
}