<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Administrator
 * @version
 * @des
 */
class Wait_delivery_workflow extends Workflow_order_abstract {
    private $_Order;
    private $_NeedPay = 0;
    private $_Payed = 0;
    private $_VirtualNeedPay = 0;
    private $_PayType;
    private $_PayStatus = false;
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function wait_delivery () {
        $this->_Workflow->store_message('订单等待发货!');
        return true;
    }

    public function delivering () {
        if ($this->_handle_delivery()) {
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['delivering']);
            return $this->_Workflow->delivering();
        } else {
            return false;
        }
    }
    public function delivered() {
        if ($this->_handle_delivery()) {
            $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['delivered']);
            return $this->_Workflow->delivered();
        } else {
            return false;
        }
    }

    private function _handle_delivery () {
        if (!!($Query = $this->_CI->order_model->select_detail(array('order_id' => $this->_Source_id)))) {
            $this->_Order = $Query['content'];
            unset($Query);
            if ($this->_edit_pay()) {
                if ($this->_PayStatus !== $this->_Order['pay_status']) { // 支付状态变化是需要修改支付状态
                    $this->_Workflow->edit_data(array(
                        'pay_status' => $this->_PayStatus,
                        'payed' => $this->_Order['sum'], // 只要安排发货，应该是全额付清才对
                        'virtual_payed' => $this->_Order['virtual_sum']
                    ));
                }
                if ($this->_Payed > 0) {
                    $this->_add_order_finance_flow();
                }
                if ($this->_NeedPay > 0) {
                    $this->_edit_dealer_finance();
                    $this->_add_dealer_account_book();
                }
                return true;
            } else {
                return false;
            }
        } else {
            $this->_Workflow->set_failue('没有找到相应的订单');
            return false;
        }
    }

    private function _edit_pay () {
        if ($this->_Order['pay_status'] == PAYED) {
            $this->_PayStatus = PAYED;
            return true;
        } else {
            $this->_Payed = $this->_NeedPay = $this->_Order['sum'] - $this->_Order['payed'];
            $this->_VirtualNeedPay = $this->_Order['virtual_sum'] - $this->_Order['virtual_payed'];
            if ($this->_NeedPay > 0) {
                if ($this->_NeedPay <= $this->_Order['dealer_balance']) {
                    $this->_PayStatus = PAYED;
                    $this->_PayType = PAY_LAST;
                    return TRUE;
                } else {
                    $this->_CI->load->model('order/application_model');
                    if ($this->_Order['payterms'] == EASY_DELIVERY && !!($this->_CI->application_model->is_passed('payterms', $this->_Source_id, $this->_Order['payterms']))) { // 余额不足，余额全部扣除
                        $this->_PayType = $this->_Order['payterms'];
                        $this->_PayStatus = PAYED;
                        return TRUE;
                    }
                    $this->_Workflow->set_failue('客户余额不足, 不能确定生产, 请联系客户打款或者向财务申请宽松发货!');
                    return false;
                }
            } else {
                return true;
            }
        }
    }

    /**
     * 添加订单支付流水
     */
    private function _add_order_finance_flow () {
        $this->_CI->load->model('order/order_finance_flow_model');
        return $this->_CI->order_finance_flow_model->insert(array(
            'order_id' => $this->_Source_id,
            'payed_money' => $this->_Payed,
            'virtual_payed_money' => $this->_VirtualNeedPay,
            'status' => YES,
            'order_status' => $this->_Order['status']
        ));
    }

    /**
     * 编辑客户财务状况
     */
    private function _edit_dealer_finance () {
        $this->_CI->load->model('dealer/dealer_model');
        return $this->_CI->dealer_model->update(array(
            'balance' => $this->_Order['dealer_balance'] - $this->_NeedPay, // 客户账户余额
            'produce' => $this->_Order['dealer_produce'] - $this->_Order['sum'], // 正在生产金额
            'delivered' => $this->_Order['dealer_delivered'] + $this->_Order['sum'], // 已发货金额
            'virtual_balance' => $this->_Order['dealer_virtual_balance'] - $this->_VirtualNeedPay,
            'virtual_produce' => $this->_Order['dealer_virtual_produce'] - $this->_Order['virtual_sum'],
            'virtual_delivered' => $this->_Order['dealer_virtual_delivered'] + $this->_Order['virtual_sum']
        ), $this->_Order['dealer_id']);
    }

    /**
     * 新增客户对账单
     * @return bool
     */
    private function _add_dealer_account_book () {
        $this->_CI->load->model('dealer/dealer_account_book_model');
        $Data = array(
            'flow_num' => date('YmdHis' . join('', explode('.', microtime(true)))),
            'dealer_id' => $this->_Order['dealer_id'],
            'in' => NO,
            'amount' => -1 * $this->_NeedPay,
            'title' => $this->_Order['num'],
            'category' => $this->_get_category(),
            'source_id' => $this->_Order['v'],
            'balance' => $this->_Order['dealer_balance'] - $this->_NeedPay,
            'remark' => '',
            'virtual_amount' => -1 * $this->_VirtualNeedPay,
            'virtual_balance' => $this->_Order['dealer_virtual_balance'] - $this->_VirtualNeedPay
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
        if ($this->_PayType == '首付') {
            return $this->_CI->config->item('dabc_first');
        } elseif ($this->_PayType == '尾款') {
            return $this->_CI->config->item('dabc_last');
        } elseif ($this->_PayType == '全款') {
            return $this->_CI->config->item('dabc_all');
        } elseif ($this->_PayType == '宽松生产') {
            return $this->_CI->config->item('dabc_easy_produce');
        } elseif ($this->_PayType == '宽松发货') {
            return $this->_CI->config->item('dabc_easy_delivery');
        } else {
            return $this->_CI->config->item('dabc_other');
        }
    }

    /**
     * 订单等待发货之后，重新入库
     * @return mixed
     */
    public function inned () {
        $this->_Workflow->store_message('订单重新入库!');
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['inned']);
        return $this->_Workflow->inned();
    }

    public function __call($name, $arguments){
        ;
    }
}