<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 */
class Produce_workflow extends Workflow_order_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }
    /**
     * 确认生产
     * @param unknown $Type
     */
    public function produce() {
        $this->_Workflow->store_message('等待优化，打印和安排生产...');
        return true;
    }

    public function re_sure () {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['wait_sure']);
        return $this->_Workflow->re_sure();
    }

    public function pre_produce(){
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['pre_produce']);
        return $this->_Workflow->pre_produce();
    }

    /**
     * 直接打包入库
     * @return mixed
     */
    public function inned () {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['inned']);
        return $this->_Workflow->inned();
    }
    /**
     * 重新拆单
     */
    public function re_dismantle(){
        $this->_clear_qrcode();
        $this->_clear_valuate();
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['dismantling']);
        return $this->_Workflow->re_dismantle();
    }
    
    /**
     * 重新核价
     */
    public function re_valuate(){
        $this->_clear_qrcode();
        $this->_clear_valuate();
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['valuating']);
        return $this->_Workflow->re_valuate();
    }

    public function __call($name, $arguments){
        ;
    }
}