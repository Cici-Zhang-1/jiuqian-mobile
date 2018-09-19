<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Remove extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }
    
    public function remove(){
        $this->_Workflow->store_message('订单产品已经作废');
        return true;
    }

    public function __call($name, $arguments){
        ;
    }
}