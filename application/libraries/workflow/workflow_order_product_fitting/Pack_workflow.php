<?php namespace Wopf;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/23
 * Time: 9:33
 */
class Pack_workflow extends Workflow_order_product_fitting_abstract {
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function pack(){
        $this->_Workflow->store_message('==等待包装打包');
        return true;
    }

    public function re_pack () {
        $this->_Workflow->store_message('岗位工作变动，重新安排打包工人!');
        return true;
    }

    public function packing () {
        $this->_Workflow->edit_current_workflow(Workflow_order_product_fitting::$AllWorkflow['packing']);
        $this->_Workflow->packing();
    }

    public function packed($arguments) {
        if (!isset($GLOBALS['creator'])) {
            $GLOBALS['creator'] = $this->_CI->session->userdata('uid');
        }
        $this->_Workflow->edit_current_workflow(Workflow_order_product_fitting::$AllWorkflow['packed'], array('pack' => $GLOBALS['creator'], 'pack_datetime' => date('Y-m-d H:i:s')));
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
        return $this->_Workflow->packed($Msg, $Data);
    }

    public function __call($name, $arguments){
        ;
    }
}