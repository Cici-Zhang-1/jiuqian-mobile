<?php namespace Wm;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 9:35
 */
class Electronic_saw_workflow extends Workflow_mrp_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function electronic_saw () {
        $this->_Workflow->store_message('等待下料');
        return true;
    }

    public function electronic_sawed() {
        $this->_Workflow->edit_current_workflow(Workflow_mrp::$AllWorkflow['electronic_sawed'], array('saw' => $this->_CI->session->userdata('uid'), 'saw_datetime' => date('Y-m-d H:i:s')));
        return $this->_Workflow->electronic_sawed();
    }

    public function re_shear () {
        $this->_Workflow->edit_current_workflow(Workflow_mrp::$AllWorkflow['shear'], array('distribution' => ZERO, 'shear' => ZERO, 'shear_datetime' => null));
        return $this->_Workflow->re_shear();
    }

    public function __call($name, $arguments){
        ;
    }
}