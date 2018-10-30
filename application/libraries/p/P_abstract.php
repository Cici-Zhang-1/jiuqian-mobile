<?php
namespace Post_sale;
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/29
 * Time: 15:18
 */
abstract class P_abstract {
    protected $_CI;
    protected $_OderProductId;
    protected $_OrderProduct = array('sum' => 0); // 新订单信息
    protected $_OrderProductInfo = array(); // 源订单信息
    private $_SumDiff = 0;

    public function __construct(){
        $this->_CI = &get_instance();
    }

    /**
     * 编辑订单产品
     * @param unknown $OrderProduct
     * @param unknown $Opid
     */
    protected function _edit_order_product(){
        if ($this->_OrderProductInfo['order_product_sum'] != $this->_OrderProduct['sum']) {
            $this->_OrderProduct['virtual_sum'] = $this->_OrderProduct['sum'];
            $this->_SumDiff = $this->_OrderProduct['sum'] - $this->_OrderProductInfo['order_product_sum'];
            $this->_CI->order_product_model->update($this->_OrderProduct, $this->_OderProductId);
            $SumDetail = json_decode($this->_OrderProductInfo['sum_detail'], true);
            if ('P' == $this->_OrderProductInfo['code']){
                $SumDetail['fitting'] = $this->_OrderProduct['sum'];
            } elseif ('G' == $this->_OrderProductInfo['code']) {
                $SumDetail['other'] = $this->_OrderProduct['sum'];
            } elseif ('F' == $this->_OrderProductInfo['code']) {
                $SumDetail['server'] = $this->_OrderProduct['sum'];
            }
            $Set = array(
                'sum_detail' => json_encode($SumDetail),
                'sum' => $this->_OrderProductInfo['sum'] + $this->_SumDiff
            );
            if ($this->_OrderProductInfo['status'] > O_WAIT_DELIVERY) {
                $Set['payed'] = $Set['sum'];
                $Set['virtual_payed'] = $Set['sum'];
            } else {
                if ($this->_SumDiff > 0 && $this->_OrderProductInfo['pay_status'] == PAYED) { // 未出厂的，如果已经支付，则改为部分支付
                    $Set['pay_status'] = PAY;
                }
            }
            $Set['virtual_sum'] = $Set['sum'];
            $this->_edit_order($Set);
            $this->_edit_dealer();
        }
        return true;
    }

    private function _edit_order ($Set) {
        $this->_CI->load->model('order/order_model');
        $this->_CI->order_model->update($Set, $this->_OrderProductInfo['order_id']);
        return true;
    }
    private function _edit_dealer () {
        $this->_CI->load->model('dealer/dealer_model');
        if ($this->_OrderProductInfo['status'] > O_WAIT_DELIVERY) {
            $this->_CI->dealer_model->update(array(
                'balance' => $this->_OrderProductInfo['dealer_balance'] - $this->_SumDiff,
                'delivered' => $this->_OrderProductInfo['dealer_delivered'] + $this->_SumDiff,
                'virtual_balance' => $this->_OrderProductInfo['dealer_virtual_balance'] - $this->_SumDiff,
                'virtual_delivered' => $this->_OrderProductInfo['dealer_virtual_delivered'] + $this->_SumDiff
            ), $this->_OrderProductInfo['dealer_id']);
            $this->_add_dealer_account_book();
        } else {
            $this->_CI->dealer_model->update(array(
                'produce' => $this->_OrderProductInfo['dealer_produce'] + $this->_SumDiff,
                'virtual_produce' => $this->_OrderProductInfo['dealer_virtual_produce'] + $this->_SumDiff
            ), $this->_OrderProductInfo['dealer_id']);
        }
        return true;
    }

    private function _add_dealer_account_book () {
        $this->_CI->load->model('dealer/dealer_account_book_model');
        $Data = array(
            'flow_num' => date('YmdHis' . join('', explode('.', microtime(true)))),
            'dealer_id' => $this->_OrderProductInfo['dealer_id'],
            'in' => $this->_SumDiff > ZERO ? NO : YES,
            'amount' => -1 * $this->_SumDiff,
            'title' => $this->_OrderProductInfo['num'],
            'category' => $this->_get_category(),
            'source_id' => $this->_OrderProductInfo['order_id'],
            'balance' => $this->_OrderProductInfo['dealer_balance'] - $this->_SumDiff,
            'remark' => '',
            'virtual_amount' => -1 * $this->_SumDiff,
            'virtual_balance' => $this->_OrderProductInfo['dealer_virtual_balance'] - $this->_SumDiff
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
        return $this->_CI->config->item('dabc_post_sale');
    }
    abstract public function edit($Save, $OrderProduct);

    /*abstract public function dismantling();
    abstract public function dismantled();*/
}