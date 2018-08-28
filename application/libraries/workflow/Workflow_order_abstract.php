<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月6日
 * @author Zhangcc
 * @version
 * @des
 */
abstract class Workflow_order_abstract{
    //定义一个环境角色，也就是封装状态的变换引起的功能变化
    protected  $_Workflow;
    protected $_Source_id;
    protected $_Source_ids;
    protected $_CI;

    public function set_workflow(Workflow_order $Workflow){
        $this->_Workflow = $Workflow;
        $this->_CI = &get_instance();
    }
    /**
     * 作废订单时起作用
     */
    public function remove(){
        if (!!($OrderProductId = $this->_are_removable())) {
            $this->_Workflow->initialize($OrderProductId);
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['remove']);
            $this->_Workflow->store_message('订单作废');
        }
    }
    /**
     * 判断订单产品是否可以删除
     * @return array|bool
     */
    private function _are_removable () {
        $this->_Source_ids = $this->_Workflow->get_source_ids();
        if(!!($Ids = $this->_CI->order_model->are_removable($this->_Source_ids))){
            $Workflow = array();
            foreach ($Ids as $key => $value){
                $Workflow[] = $value['v'];
            }
            if(!empty($Workflow)){
                return $Workflow;
            }
        }
        return false;
    }

    /**
     * 清理没有BD文件的二维码
     */
    protected function _clear_qrcode() {
        $SourceIds = $this->_Workflow->get_source_ids();
        $this->_CI->load->model('order/order_product_board_plate_model');
        if(!!($this->_CI->order_product_board_plate_model->update_clear_qrcode($SourceIds))){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 重新拆单就需要重新计价
     * @return bool
     */
    protected function _clear_valuate () {
        $SourceIds = $this->_Workflow->get_source_ids();
        $this->_CI->load->model('order/order_product_board_plate_model');
        if(!!($this->_CI->order_product_board_plate_model->update_clear_qrcode($SourceIds))){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 判断是否已经计价
     * @return bool
     */
    protected function _is_valuated(){
        $this->_CI->load->model('data/configs_model');
        if ($this->_CI->configs_model->select_by_name('allow_zero_valuate')) {
            return true;
        } elseif (!!($Query = $this->_CI->order_model->select_detail(array('order_id' => $this->_Source_id)))) {
            if($Query['content']['sum'] > 0 || $Query['content']['order_type'] == 'B'){
                return true;
            }
        }
        return false;
    }

    protected function _is_all_inned () {
        $this->_CI->load->model('order/order_product_model');
        $Set = array(
            'pack' => 0,
            'pack_detail' => array()
        );
        if(!!($Inned = $this->_CI->order_product_model->is_all_inned($this->_Source_id))){
            foreach ($Inned as $key=>$value) {
                $Set['pack'] += $value['pack'];
                if(!isset($Set['pack_detail'][$value['code']])){
                    $Set['pack_detail'][$value['code']] = $value['pack'];
                }else{
                    $Set['pack_detail'][$value['code']] += $value['pack'];
                }
                if ($value['status'] != OP_INED) {
                    $this->_Inned = false;
                }
            }
            unset($Inned);
        }
        $Set['pack_detail'] = json_encode($Set['pack_detail']);
        return $Set;
    }
    /**
     * 执行某个已经执行过的操作
     */
    protected function _execute_record ($Name) {
        if(!!($Query = $this->_CI->workflow_order_model->select_by_name($Name))) {
            $this->_Workflow->store_message('执行了' . $Query['label'] . '操作');
        } else {
            $this->_Workflow->store_message('执行了无效的操作');
        }
    }
}