<?php namespace Wopc;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
class Optimize_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }
    
    public function optimize(){
        $this->_Workflow->store_message('订单产品分类已经优化');
        if ($this->_workflow_propagation(__FUNCTION__)) {
            return $this->_workflow_next();
        }
        return false;
    }

    public function __call($name, $arguments){
        ;
    }
}