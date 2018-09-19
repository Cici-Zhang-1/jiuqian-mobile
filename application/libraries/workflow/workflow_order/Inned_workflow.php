<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月16日
 * @author Administrator
 * @version
 * @des
 * 完全入库
 */
class Inned_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function inned() {
        $Inned = $this->_is_all_inned();
        $this->_Workflow->set_data($Inned);
        if ($this->_Inned) {
            $this->_Workflow->store_message('订单产品已经全部入库!');
            $this->_Workflow->set_datetime(array('inned' => $this->_CI->session->userdata('uid'), 'inned_datetime' => date('Y-m-d H:i:s')));
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['wait_delivery']);
            return $this->_Workflow->wait_delivery();
        } else {
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['inning']);
            return $this->_Workflow->inning();
        }
    }

    public function __call($name, $arguments){
        ;
    }
}