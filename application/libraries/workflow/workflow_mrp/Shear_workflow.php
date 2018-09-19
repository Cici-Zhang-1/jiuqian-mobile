<?php namespace Wm;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20
 * Time: 9:33
 */
class Shear_workflow extends Workflow_mrp_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function shear(){
        $this->_Workflow->store_message('已经上传SAW文件, 等待排产');
        return true;
    }

    public function re_shear () {
        $this->_Workflow->store_message('岗位变动或安排出错，重新安排下料工人!');
        return $this->_workflow_propagation(__FUNCTION__);
    }

    public function sheared() {
        $this->_Workflow->edit_current_workflow(Workflow_mrp::$AllWorkflow['sheared'], array('shear' => $this->_CI->session->userdata('uid'), 'shear_datetime' => date('Y-m-d H:i:s')));
        return $this->_Workflow->sheared();
    }

    public function __call($name, $arguments){
        ;
    }
}