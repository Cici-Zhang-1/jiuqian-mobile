<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author zhangcc
 * @version
 * @des
 */
class Outed_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function outed () {
        $this->_Workflow->store_message('订单已出厂!');
        return true;
    }

    public function __call($name, $arguments){
        ;
    }
}