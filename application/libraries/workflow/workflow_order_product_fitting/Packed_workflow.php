<?php namespace Wopf;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:33
 */
class Packed_workflow extends Workflow_order_product_fitting_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function packed($arguments) {
        if (count($arguments) == TWO) {
            $Msg = $arguments[0];
            $Data = $arguments[1];
        } else {
            $arguments = array_pop($arguments);
            if (is_array($arguments)) {
                $Data = $arguments;
                $Msg = '';
            } else {
                $Msg = $arguments;
                $Data = array();
            }
        }
        $this->_Workflow->store_message('==配件已经打包');
        unset($Data['packed']);
        return $this->_workflow_propagation('packed', $Msg, $Data);
    }

    public function __call($name, $arguments){
        ;
    }
}