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
    protected $_Inned;

    public function set_workflow(Workflow_order $Workflow){
        $this->_Workflow = $Workflow;
        $this->_CI = &get_instance();
    }
    /**
     * 作废订单时起作用
     */
    public function remove(){
        if (!!($Order = $this->_are_removable())) {
            $this->_CI->load->model('dealer/dealer_model');
            $this->_CI->load->model('dealer/dealer_account_book_model');
            $Vs = array();
            $this->_CI->order_model->trans_begin();
            foreach ($Order as $Key => $Value) {
                if ($Value['status'] > O_WAIT_SURE && $Value['payed'] > ZERO) { // 如果订单是已经确认之后的
                    if (!($this->_edit_dealer_finance($Value) && $this->_add_dealer_account_book($Value))) {
                        break;
                    }
                }
                array_push($Vs, $Value['v']);
            }
            $this->_Workflow->initialize($Vs);
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['remove'], array(
                'payed' => ZERO,
                'virtual_payed' => ZERO
            ));
            $this->_Workflow->store_message('订单作废');
            if ($this->_CI->order_model->trans_status() === FALSE) {
                $this->_CI->order_model->trans_rollback();
                $this->_Workflow->set_failue('执行作废操作时, 发生未知错误!');
                return false;
            } else {
                if (empty($this->_Workflow->get_failue())) {
                    $this->_CI->order_model->trans_commit();
                    return true;
                } else {
                    $this->_CI->order_model->trans_rollback();
                    return false;
                }
            }
        } else {
            $this->_Workflow->set_failue('订单已经作废或者已经出厂，不能作废!');
            return false;
        }
    }

    /**
     * 编辑客户财务状况
     * @param $Order
     * @return mixed
     */
    private function _edit_dealer_finance ($Order) {
        if ($this->_CI->dealer_model->update(array(
            'balance' => $Order['dealer_balance'] + $Order['payed'],
            'produce' => $Order['dealer_produce'] - $Order['sum'],
            'virtual_balance' => $Order['dealer_virtual_balance'] + $Order['virtual_payed'],
            'virtual_produce' => $Order['dealer_virtual_produce'] - $Order['virtual_sum']
        ), $Order['dealer_id'])) {
            return true;
        } else {
            $this->_Workflow->set_failue('修改客余额时出错!');
            return false;
        }
    }

    /**
     * 新建客户流水账
     * @param $Order
     * @return bool
     */
    private function _add_dealer_account_book ($Order) {
        $Data = array(
            'flow_num' => date('YmdHis' . join('', explode('.', microtime(true)))),
            'dealer_id' => $Order['dealer_id'],
            'in' => YES,
            'amount' => $Order['payed'],
            'title' => $Order['order_num'],
            'category' => $this->_get_category(),
            'source_id' => $Order['order_id'],
            'balance' => $Order['dealer_balance'] + $Order['payed'],
            'remark' => '',
            'virtual_amount' => $Order['virtual_payed'],
            'virtual_balance' => $Order['dealer_virtual_balance'] + $Order['virtual_payed']
        );
        if ($this->_CI->dealer_account_book_model->insert($Data)) {
            return true;
        } else {
            $this->_Workflow->set_failue('新建客户流水账失败!');
            return false;
        }
    }
    private function _get_category () {
        $this->_CI->config->load('defaults/dealer_account_book_category');
        return $this->_CI->config->item('dabc_order_remove');
    }
    /**
     * 判断订单产品是否可以删除
     * @return array|bool
     */
    private function _are_removable () {
        $this->_Source_ids = $this->_Workflow->get_source_ids();
        if(!!($Ids = $this->_CI->order_model->are_removable($this->_Source_ids))){
            return $Ids;
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
        $this->_Inned = true;
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