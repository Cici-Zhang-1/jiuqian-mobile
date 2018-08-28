<?php namespace Wm;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 9:35
 */
class Electronic_sawed_workflow extends Workflow_mrp_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function electronic_sawed () {
        $this->_Workflow->store_message('已下料');
        $this->_workflow_propagation(__FUNCTION__);
    }
    public function __call($name, $arguments){
        ;
    }
}