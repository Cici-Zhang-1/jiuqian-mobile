<?php namespace Wopc;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:34
 */
class Edge_workflow extends Workflow_order_product_classify_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function edge () {
        $this->_Workflow->store_message('等待封边');
    }

    public function re_edge () {
        $this->_Workflow->store_message('岗位工作变动，重新安排封边工人!');
    }

    public function edging(){
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['edging'], array('edge' => $this->_CI->session->userdata('uid')));
        $this->_Workflow->edging();
    }

    public function edged(){
        $this->_Workflow->edit_current_workflow(Workflow_order_product_classify::$AllWorkflow['edged'], array('edge' => $this->_CI->session->userdata('uid'), 'edge_datetime' => date('Y-m-d H:i:s')));
        $this->_Workflow->edged();
    }

    public function __call($name, $arguments){
        ;
    }
}