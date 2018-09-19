<?php namespace Wm;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/6
 * Time: 8:36
 */
class Remove_workflow extends Workflow_mrp_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function remove () {
        $this->_Workflow->store_message('MRP作废');
        return true;
    }

    public function re_shear () {
        $this->_Workflow->edit_current_workflow(Workflow_mrp::$AllWorkflow['shear'], array('distribution' => ZERO));
        return $this->_Workflow->re_shear();
    }

    public function __call($name, $arguments){
        ;
    }
}