<?php namespace Wop;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author Administrator
 * @version
 * @des
 * 订单产品已经包装完
 */
class Packed_workflow extends Workflow_order_product_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function packed(){
        $this->_Workflow->store_message('订单产品已经全部打包!');
        $this->_Workflow->edit_current_workflow(Workflow_order_product::$AllWorkflow['inned'], array('inned' => $this->_CI->session->userdata('uid'), 'inned_datetime' => date('Y-m-d H:i:s')));
        return $this->_Workflow->inned();
    }

    public function __call($name, $arguments){
        // $this->_execute_record($name);
    }
}