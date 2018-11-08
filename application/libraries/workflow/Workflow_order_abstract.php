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
     * 订单直接出厂
     * @return bool
     */
    public function direct_out () {
        if (!!($Order = $this->_are_directable())) {
            $this->_CI->load->model('dealer/dealer_model');
            $this->_CI->load->model('dealer/dealer_account_book_model');
            $this->_CI->order_model->trans_begin();
            foreach ($Order as $Key => $Value) {
                if ($Value['sum'] > ZERO && $Value['pay_status'] != PAYED) { // 如果订单是已经确认之后的
                    if ($this->_add_order_finance_flow($Value) && !($this->_edit_dealer_finance($Value, YES) && $this->_add_dealer_account_book($Value, YES))) {
                        break;
                    }
                }
                $this->_Workflow->initialize($Value['v']);
                $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['outed'], array(
                    'payed' => $Value['sum'],
                    'virtual_payed' => $Value['virtual_sum'],
                    'pay_status' => PAYED
                ));
                $this->_Workflow->store_message('订单直接出厂');
            }
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
            $this->_Workflow->set_failue('订单还没有确认或者已经出厂，不能直接出厂!');
            return false;
        }
    }

    /**
     * 添加订单支付流水
     * @return mixed
     */
    private function _add_order_finance_flow ($Order) {
        $this->_CI->load->model('order/order_finance_flow_model');
        $Diff = $Order['sum'] - $Order['payed'];
        $VirtualDiff = $Order['virtual_sum'] - $Order['virtual_payed'];
        return $this->_CI->order_finance_flow_model->insert(array(
            'order_id' => $Order['order_id'],
            'payed_money' => $Diff,
            'virtual_payed_money' => $VirtualDiff,
            'status' => YES,
            'order_status' => $Order['status']
        ));
    }

    /**
     * 编辑客户财务状况
     * @param $Order
     * @return mixed
     */
    private function _edit_dealer_finance ($Order, $Direct = false) {
        if ($Direct) {
            $Diff = $Order['sum'] - $Order['payed'];
            $VirtualDiff = $Order['virtual_sum'] - $Order['virtual_payed'];
            $Set = array(
                'balance' => $Order['dealer_balance'] - $Diff,
                'produce' => $Order['dealer_produce'] - $Order['sum'],
                'delivered' => $Order['dealer_delivered'] + $Order['sum'],
                'virtual_balance' => $Order['dealer_virtual_balance'] - $VirtualDiff,
                'virtual_produce' => $Order['dealer_virtual_produce'] - $Order['virtual_sum'],
                'virtual_delivered' => $Order['dealer_virtual_delivered'] + $Order['virtual_sum']
            );
        } else {
            $Set = array(
                'balance' => $Order['dealer_balance'] + $Order['payed'],
                'produce' => $Order['dealer_produce'] - $Order['sum'],
                'virtual_balance' => $Order['dealer_virtual_balance'] + $Order['virtual_payed'],
                'virtual_produce' => $Order['dealer_virtual_produce'] - $Order['virtual_sum']
            );
        }
        if ($this->_CI->dealer_model->update($Set, $Order['dealer_id'])) {
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
    private function _add_dealer_account_book ($Order, $Direct) {
        $Data = array(
            'flow_num' => date('YmdHis' . join('', explode('.', microtime(true)))),
            'dealer_id' => $Order['dealer_id'],
            'title' => $Order['order_num'],
            'category' => $this->_get_category($Direct),
            'source_id' => $Order['order_id'],
            'remark' => '订单金额￥' . $Order['sum']
        );
        if ($Direct) {
            $Data['in'] = NO;
            $Data['amount'] = $Order['payed'] - $Order['sum'];
            $Data['virtual_amount'] = $Order['virtual_payed'] - $Order['virtual_sum'];
            $Data['balance'] = $Order['dealer_balance'] + $Data['amount'];
            $Data['virtual_balance'] = $Order['dealer_virtual_balance'] + $Data['virtual_amount'];
            $Data['inside'] = NO;
            $Data['status'] = $Order['status'];
        } else {
            $Data['in'] = YES;
            $Data['amount'] = $Order['payed'];
            $Data['virtual_amount'] = $Order['virtual_payed'];
            $Data['balance'] = $Order['dealer_balance'] + $Order['payed'];
            $Data['virtual_balance'] = $Order['dealer_virtual_balance'] + $Order['virtual_payed'];
            $Data['inside'] = YES;
            $Data['status'] = $Order['status'];
        }
        if ($this->_CI->dealer_account_book_model->insert($Data)) {
            return true;
        } else {
            $this->_Workflow->set_failue('新建客户流水账失败!');
            return false;
        }
    }
    private function _get_category ($Direct) {
        $this->_CI->config->load('defaults/dealer_account_book_category');
        if ($Direct) {
            return $this->_CI->config->item('dabc_order_direct');
        } else {
            return $this->_CI->config->item('dabc_order_remove');
        }
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
     * 判断是否可以直接出厂
     * @return bool || array
     */
    private function _are_directable () {
        $this->_Source_ids = $this->_Workflow->get_source_ids();
        if(!!($Ids = $this->_CI->order_model->are_directable($this->_Source_ids))){
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