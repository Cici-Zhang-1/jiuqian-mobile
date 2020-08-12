<?php namespace Wo;
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 */
class Wait_sure_workflow extends Workflow_order_abstract {
    private $_OrderProduct;
    private $_Order;
    private $_NeedPay;
    private $_VirtualNeedPay;
    private $_PayType;
    private $_PayStatus;
    private $_OnlyServer = true;
    private $_Classify = array();

    private $_Bd = array();
    private $_UnBdCount = ONE;
    public function __construct($Source_id){
        $this->_Source_id = $Source_id;
    }

    public function wait_sure () {
        $this->_Workflow->store_message('等待确认生产');
        return true;
    }
    /**
     * 确认生产
     * @return bool
     */
    public function produce() {
        $this->_CI->load->model('order/order_product_model');
        if (!!($this->_OrderProduct = $this->_CI->order_product_model->select_by_order_id($this->_Source_id))) {
            $this->_is_only_server();
            $this->_Order = $this->_OrderProduct[array_rand($this->_OrderProduct, ONE)];
            if ($this->_edit_pay()) {
                $this->_Workflow->edit_data(array(
                    'pay_status' => $this->_PayStatus,
                    'payed' => $this->_NeedPay,
                    'virtual_payed' => $this->_VirtualNeedPay
                ));
                $this->_CI->order_model->trans_begin();
                foreach ($this->_OrderProduct as $Key => $Value) {
                    if ($Value['code'] == CABINET_NUM || $Value['code'] == WARDROBE_NUM) {
                        if (!($this->_edit_qrcode_and_classify($Value['order_product_id']))) {
                            $this->_Workflow->set_failue('设置Qrcode和板块分类时发生错误');
                            return false;
                        }
                    } elseif ($Value['code'] == DOOR_NUM || $Value['code'] == WOOD_NUM) {
                        if (!($this->_edit_order_product_board($Value))) {
                            $this->_Workflow->set_failue('设置门板或木框门流程状态时出错');
                            return false;
                        }
                    } elseif ($Value['code'] == FITTING_NUM) {
                        if (!($this->_edit_fitting($Value))) {
                            $this->_Workflow->set_failue('设置配件产品流程状态时出错');
                            return false;
                        }
                    } elseif ($Value['code'] == OTHER_NUM) {
                        if (!($this->_edit_other($Value))) {
                            $this->_Workflow->set_failue('设置外购产品流程状态时出错');
                            return false;
                        }
                    }
                    $this->_OrderProduct[$Key] = $Value;
                }
                if ($this->_NeedPay > ZERO) {
                    $this->_add_order_finance_flow();
                    $this->_edit_dealer_finance();
                    $this->_add_dealer_account_book();
                } else {
                    $this->_edit_dealer_status();
                }
                if ($this->_OnlyServer) {
                    $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['outed']);
                    $this->_Workflow->outed();
                } else {
                    $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['produce']);
                    $this->_Workflow->produce();
                }
                $this->_Workflow->set_datetime(array('sure' => $this->_CI->session->userdata('uid'), 'sure_datetime' => date('Y-m-d H:i:s')));
                if ($this->_CI->order_model->trans_status() === FALSE) {
                    $this->_CI->order_model->trans_rollback();
                    $this->_Workflow->set_failue('这次执行操作, 发生未知错误!');
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
            }
            return false;
        } else {
            $this->_Workflow->set_failue('这个订单没有产品');
            return false;
        }
    }

    private function _is_only_server () {
        foreach ($this->_OrderProduct as $Key => $Value) {
            if ($Value['code'] != SERVER_NUM) {
                $this->_OnlyServer = false;
            }
        }
        return $this->_OnlyServer;
    }

    /**
     * 编辑订单的支付状况
     */
    private function _edit_pay () {
        if ($this->_Order['sum'] == ZERO) {
            $this->_PayStatus = PAYED;
            $this->_NeedPay = ZERO;
            $this->_VirtualNeedPay = ZERO;
            return true;
        } else {
            if ($this->_OnlyServer) {
                $this->_Order['down_payment'] = ONE;  // 当只有服务类商品时必须是一次性付款
            }
            $this->_NeedPay = floor($this->_Order['sum'] * $this->_Order['down_payment']);
            $this->_VirtualNeedPay = floor($this->_Order['virtual_sum'] * $this->_Order['down_payment']);
            if ($this->_NeedPay <= $this->_Order['dealer_balance']) {
                if ($this->_Order['down_payment'] == ONE) {
                    $this->_PayStatus = PAYED;
                    $this->_PayType = PAY_FULL;
                } else {
                    $this->_PayStatus = PAY;
                    $this->_PayType = PAY_FIRST;
                }
                return TRUE;
            } else {
                $this->_CI->load->model('order/application_model');
                if (($this->_Order['payterms'] == EASY_PRODUCE || $this->_Order['payterms'] == EASY_DELIVERY) && !!($this->_CI->application_model->is_passed('payterms', $this->_Source_id, $this->_Order['payterms']))) { // 余额不足，余额全部扣除
                    $this->_PayType = $this->_Order['payterms'];
                    // $this->_NeedPay = $this->_Order['dealer_balance'];
                    // $this->_VirtualNeedPay = $this->_Order['dealer_balance'];
                    if ($this->_NeedPay == ZERO) {
                        $this->_PayStatus = UNPAY;
                    } else {
                        $this->_PayStatus = PAY;
                    }
                    return TRUE;
                }
                $this->_Workflow->set_failue('客户余额不足, 不能确定生产, 请联系客户打款或者向财务申请宽松生产!');
                return false;
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
            'payed_money' => $this->_NeedPay,
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
            'balance' => bcsub($this->_Order['dealer_balance'], $this->_NeedPay, 2),
            'produce' => bcadd($this->_Order['dealer_produce'], $this->_Order['sum'], 2),
            'virtual_balance' => bcsub($this->_Order['dealer_virtual_balance'], $this->_VirtualNeedPay, 2),
            'virtual_produce' => bcadd($this->_Order['dealer_virtual_produce'], $this->_Order['virtual_sum'], 2),
            /*'balance' => $this->_Order['dealer_balance'] - $this->_NeedPay,
            'produce' => $this->_Order['dealer_produce'] + $this->_Order['sum'],
            'virtual_balance' => $this->_Order['dealer_virtual_balance'] - $this->_VirtualNeedPay,
            'virtual_produce' => $this->_Order['dealer_virtual_produce'] + $this->_Order['virtual_sum'],*/
            'last_order' => date('Y-m-d H:i:s')
        ), $this->_Order['dealer_id']);
    }

    /**
     * 更新客户状态
     */
    private function _edit_dealer_status () {
        $this->_CI->load->model('dealer/dealer_model');
        return $this->_CI->dealer_model->update(array(
            'last_order' => date('Y-m-d H:i:s')
        ), $this->_Order['dealer_id']);
    }
    /**
     * 新建客户流水账
     */
    private function _add_dealer_account_book () {
        $this->_CI->load->model('dealer/dealer_account_book_model');
        $Data = array(
            'flow_num' => date('YmdHis' . join('', explode('.', microtime(true)))),
            'dealer_id' => $this->_Order['dealer_id'],
            'in' => $this->_NeedPay > ZERO ? NO : YES,
            'amount' => -1 * $this->_NeedPay,
            'title' => $this->_Order['order_num'],
            'category' => $this->_get_category(),
            'source_id' => $this->_Order['order_id'],
            'balance' => bcsub($this->_Order['dealer_balance'], $this->_NeedPay, 2),
            'remark' => '订单金额￥' . $this->_Order['sum'],
            'virtual_amount' => -1 * $this->_VirtualNeedPay,
            'virtual_balance' => bcsub($this->_Order['dealer_virtual_balance'], $this->_VirtualNeedPay, 2),
            'inside' => $this->_NeedPay > ZERO ? NO : YES,
            'source_status' => $this->_Order['status'] // 订单状态
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
        } elseif ($this->_PayType == '撤单') {
            return $this->_CI->config->item('dabc_withdraw');
        } else {
            return $this->_CI->config->item('dabc_other');
        }
    }

    /**
     * 更新Qrcode和板块分类
     * @param $OrderProductId
     * @return bool
     */
    private function _edit_qrcode_and_classify ($OrderProductId) {
        $this->_CI->load->model('order/order_product_board_plate_model');
        if (!!($Qrcode = $this->_CI->order_product_board_plate_model->select_for_sure($OrderProductId))) {
            $this->_CI->load->model('order/order_product_classify_model');
            $this->_UnBdCount = ONE;
            $this->_Bd = array();
            foreach ($Qrcode as $Key => $Value) {
                $Value['order_product_classify_id'] = $this->_get_order_product_classify_id($Value);
                if ($Tmp = $this->_get_qrcode($Value)) {
                    $Value = array_merge($Value, $Tmp);
                    $Qrcode[$Key] = $Value;
                } else {
                    unset($Qrcode[$Key]);
                }
            }
            if(!empty($this->_Bd)){
                foreach ($this->_Bd as $Key => $Value){
                    $No = sprintf('%0'.QRCODE_SUFFIX.'d', $this->_UnBdCount);
                    $Value['qrcode'] = $Value['qrcode'] . $No;
                    $Value['plate_num'] = $No;
                    array_push($Qrcode, $Value);
                    $this->_UnBdCount++;
                }
            }
            return $this->_edit_order_product_board_plate($Qrcode) && $this->_edit_order_product_classify();
        }
        return true;
    }
    /**
     * 编辑Qrcode
     * @param $OrderProductId
     * @return bool
     */
    private function _get_qrcode ($BoardPlate) {
        if(empty($BoardPlate['cubicle_num'])){
            $BoardPlate['cubicle_num'] = 0;
        }
        if(0 == $BoardPlate['bd']){
            if(empty($BoardPlate['qrcode'])) {
                /*不是BD文件 没有提前设置Qrcode*/
                $this->_UnBdCount++;
                $No = sprintf('%0'.QRCODE_SUFFIX.'d', $this->_UnBdCount);
                $BoardPlate['qrcode'] = $BoardPlate['order_product_num'].'-'.$BoardPlate['cubicle_num'].$No;
                $BoardPlate['plate_num'] = $No;
            } else {
                /**不是Bd文件, 但是提前设置的Qrcode */
                $Qrcodes = explode('-', $BoardPlate['qrcode']);
                $Suffix = array_pop($Qrcodes);
                $Cubicle = array_pop($Qrcodes);
                $Cubicle_num = intval(substr($Cubicle, 1));
                $No = ltrim($Suffix, $Cubicle_num);
                $BoardPlate['plate_num'] = $No;
                $BoardPlate['cubicle_num'] = $Cubicle_num;
            }
        }else{
            /*BD文件*/
            /*BD文件上传后的手工板块的后缀命名不包含柜号,从1开始*/
            /*已经有Qrcode的则不需要改动*/
            if(empty($BoardPlate['qrcode'])){
                /*BD文件上传之后手动添加新的板块*/
                $BoardPlate['qrcode'] = $BoardPlate['order_product_num'] . '-';
                $this->_Bd[] = $BoardPlate;
                return false;
            }else{
                $Tmp = explode('-', $BoardPlate['qrcode']);
                $Last = array_pop($Tmp);
                if(QRCODE_SUFFIX == strlen($Last)){
                    if(intval($Last) > $this->_UnBdCount){
                        $this->_UnBdCount = intval($Last) + 1;
                    }
                }
            }
        }
        return $BoardPlate;
    }

    private function _edit_order_product_board_plate ($Data) {
        return $this->_CI->order_product_board_plate_model->update_batch($Data);
    }


    /**
     * 更新板块分类
     * @param unknown $Oids
     */
    private function _edit_order_product_classify(){
        $this->_CI->load->model('order/order_product_classify_model');
        if (!empty($this->_Classify)) {
            return $this->_CI->order_product_classify_model->update_batch($this->_Classify);
        }
        return true;
    }

    /**
     * 获取订单产品板块分类ID
     * @param $BoardPlate
     * @return mixed
     */
    private function _get_order_product_classify_id ($BoardPlate) {
        $Classify = $this->_get_classify($BoardPlate);
        $Classify['board'] = $BoardPlate['board'];
        $Classify['order_product_id'] = $BoardPlate['order_product_id'];
        $Key = implode('', $Classify);
        if (!isset($this->_Classify[$Key])) {
            $this->_Classify[$Key] = $Classify;
            $this->_Classify[$Key]['amount'] = $BoardPlate['amount'];
            $this->_Classify[$Key]['area'] = $BoardPlate['area'];

            if(!($this->_Classify[$Key]['v'] = $this->_CI->order_product_classify_model->is_exist($Classify))){
                $this->_Classify[$Key]['v'] = $this->_CI->order_product_classify_model->insert($Classify);
            }
        } else {
            $this->_Classify[$Key]['amount'] += $BoardPlate['amount'];
            $this->_Classify[$Key]['area'] = bcadd($this->_Classify[$Key]['area'], $BoardPlate['area']);
            // $this->_Classify[$Key]['area'] += $BoardPlate['area'];
        }
        return $this->_Classify[$Key]['v'];
    }
    /**
     * 区分板块
     * @param unknown $Data
     */
    private function _get_classify($Data){
        static $Classify;
        static $Standard;
        if(empty($Classify)){
            $this->_CI->load->model('data/classify_model');
            $Classify = $this->_CI->classify_model->select_children();
            $Standard = $this->_CI->classify_model->select_by_name('标准板块');
        }
        $Return = array(
            'classify_id' => $Standard['v'],
            'production_line' => $Standard['production_line'] // 生产线
        );
        $Parent = ZERO;
        $ProductionLine = ZERO;
        $Flag = true;
        if (!empty($Classify)) {
            $MaxFlag = 0;
            $Max = 0;
            foreach ($Classify as $key => $value){
                if($value['plate'] != ''){
                    if ($value['plate'] != $Data['plate_name']) {
                        $Flag = false;
                    } else {
                        $Max++;
                    }
                }
                if($value['width_min'] < $value['width_max'] && $value['length_min'] < $value['length_max']){   /*Length + Width*/
                    if(!(($Data['width'] >= $value['width_min'] && $Data['width'] < $value['width_max']) ||
                        ($Data['length'] >= $value['length_min'] && $Data['length'] < $value['length_max']))){
                        $Flag = false;
                    } else {
                        $Max++;
                    }
                }elseif ($value['width_min'] < $value['width_max'] && $value['length_min'] == $value['length_max']){    /*Width*/
                    if(!($Data['width'] >= $value['width_min'] && $Data['width'] < $value['width_max'])){
                        $Flag = false;
                    } else {
                        $Max++;
                    }
                }elseif ($value['width_min'] == $value['width_max'] && $value['length_min'] < $value['length_max']){    /*Length*/
                    if(!($Data['length'] >= $value['length_min'] && $Data['length'] < $value['length_max'])){
                        $Flag = false;
                    } else {
                        $Max++;
                    }
                }

                if($value['thick'] != 0){
                    if ($value['thick'] != $Data['thick']) {
                        $Flag = false;
                    } else {
                        $Max++;
                    }
                }
                if($value['edge'] != ''){
                    if ($value['edge'] != $Data['edge']) {
                        $Flag = false;
                    } else {
                        $Max++;
                    }
                }
                if($value['slot'] != ''){
                    if ($value['slot'] != $Data['slot']) {
                        $Flag = false;
                    } else {
                        $Max++;
                    }
                }
                if ($value['decide_size'] != '') {
                    if ($value['decide_size'] != $Data['decide_size']) {
                        $Flag = false;
                    } else {
                        $Max++;
                    }
                }
                if ($value['abnormity'] != ZERO) {
                    if ($value['abnormity'] != $Data['abnormity']) {
                        $Flag = false;
                    } else {
                        $Max++;
                    }
                }
                if($value['remark'] != ''){
                    if (!(preg_match('/'.$value['remark'].'/', $Data['remark']))) {
                        $Flag = false;
                    } else {
                        $Max++;
                    }
                }
                if(true == $Flag){
                    if ($Max > $MaxFlag) {
                        $Parent = $value['parent'];
                        $ProductionLine = $value['production_line'];
                        $MaxFlag = $Max;
                        $Max = 0;
                    }
                    // break;
                } else {
                    $Flag = true;
                }
            }
        }
        if (!empty($Parent)) {
            $Return['classify_id'] = $Parent;
            $Return = array_merge($Return, $this->_get_procedure_workflow($ProductionLine));
        } else {
            $Return = array_merge($Return, $this->_get_procedure_workflow($Return['production_line']));
        }
        return $Return;
    }

    /**
     * 编辑订单产品板块工作流
     * @param $OrderProduct
     * @return bool
     */
    private function _edit_order_product_board ($OrderProduct) {
        $this->_CI->load->model('order/order_product_board_model');
        $ProductionLine = $this->_get_product_production_line($OrderProduct['code']);
        if (!!($Board = $this->_CI->order_product_board_model->select_for_sure($OrderProduct['order_product_id']))) {
            foreach ($Board as $Key => $Value) {
                $Board[$Key] = array_merge($Value, $ProductionLine);
            }
            $this->_CI->order_product_board_model->update_batch($Board);
        }
        return true;
    }

    /**
     * 编辑订单产品配件工作流
     * @param $OrderProduct
     * @return bool
     */
    private function _edit_fitting ($OrderProduct) {
        $this->_CI->load->model('order/order_product_fitting_model');
        $ProductionLine = $this->_get_product_production_line($OrderProduct['code']);
        if (!!($Fitting = $this->_CI->order_product_fitting_model->select_for_sure($OrderProduct['order_product_id']))) {
            foreach ($Fitting as $Key => $Value) {
                $Fitting[$Key] = array_merge($Value, $ProductionLine);
            }
            $this->_CI->order_product_fitting_model->update_batch($Fitting);
        }
        return true;
    }

    /**
     * 编辑订单产品外购工作流
     * @param $OrderProduct
     * @return bool
     */
    private function _edit_other ($OrderProduct) {
        $this->_CI->load->model('order/order_product_other_model');
        $ProductionLine = $this->_get_product_production_line($OrderProduct['code']);
        if (!!($Other = $this->_CI->order_product_other_model->select_for_sure($OrderProduct['order_product_id']))) {
            foreach ($Other as $Key => $Value) {
                $Other[$Key] = array_merge($Value, $ProductionLine);
            }
            $this->_CI->order_product_other_model->update_batch($Other);
        }
        return true;
    }
    private function _get_product_production_line ($Code) {
        static $Product = array();
        if (empty($Product[$Code])) {
            $this->_CI->load->model('product/product_model');
            $Product[$Code] = $this->_CI->product_model->select_by_code($Code);
        }
        return $this->_get_procedure_workflow($Product[$Code]['production_line']);
    }
    /**
     * 获取工序工作流
     * @param $ProductionLine integer
     * @return array
     */
    private function _get_procedure_workflow ($ProductionLine) {
        static $ProductionLineProcedure;
        if (empty($ProductionLineProcedure)) {
            $this->_CI->load->model('data/production_line_procedure_model');
            if (!!($Query = $this->_CI->production_line_procedure_model->select())) {
                foreach ($Query as $Key => $Value) {
                    $ProductionLineProcedure[$Value['production_line']] = $Value;
                }
            }
        }
        return $ProductionLineProcedure[$ProductionLine];
    }

    /**
     * 订单重新确认
     */
    public function re_sure () {
        $this->_CI->load->model('order/order_product_model');
        if (!!($this->_OrderProduct = $this->_CI->order_product_model->select_by_order_id($this->_Source_id))) {
            $this->_Order = $this->_OrderProduct[array_rand($this->_OrderProduct, ONE)];
            foreach ($this->_OrderProduct as $Key => $Value) {
                $this->_OrderProduct[$Key] = $Value['order_product_id'];
            }
            if ($this->_Order['payed'] > ZERO) {
                $this->_clear_dealer_finance();
                $this->_clear_order_product_finance_flow();
                $this->_NeedPay = -1 * $this->_Order['payed'];
                $this->_PayType = '撤单';
                $this->_add_dealer_account_book();
            }
            if ($this->_Order['payterms'] == EASY_PRODUCE) {
                $this->_clear_application();
            }
            $this->_clear_order_product_board();
            $this->_clear_order_product_board_plate();
            $this->_clear_order_product_classify();
        }
        $this->_Workflow->set_data(array(
                'pay_status' => UNPAY,
                'payterms' => NORMAL_PAY,
                'payed' => ZERO,
                'virtual_payed' => ZERO
            ));
        $this->_Workflow->set_datetime(array(
            'sure' => ZERO,
            'sure_datetime' => null));
        $this->_Workflow->store_message('重新安排确认订单');
    }

    private function _clear_dealer_finance() {
        $this->_CI->load->model('dealer/dealer_model');
        return $this->_CI->dealer_model->update(array(
            'balance' => bcadd($this->_Order['dealer_balance'], $this->_Order['payed'], 2),
            'produce' => bcsub($this->_Order['dealer_produce'], $this->_Order['sum'], 2),
            'virtual_balance' => bcadd($this->_Order['dealer_virtual_balance'], $this->_Order['virtual_payed'], 2),
            'virtual_produce' => bcsub($this->_Order['dealer_virtual_produce'], $this->_Order['virtual_sum'], 2)
            /*'balance' => $this->_Order['dealer_balance'] + $this->_Order['payed'],
            'produce' => $this->_Order['dealer_produce'] - $this->_Order['sum'],
            'virtual_balance' => $this->_Order['dealer_virtual_balance'] + $this->_Order['virtual_payed'],
            'virtual_produce' => $this->_Order['dealer_virtual_produce'] - $this->_Order['virtual_sum']*/
        ), $this->_Order['dealer_id']);
    }
    private function _clear_order_product_finance_flow () {
        $this->_CI->load->model('order/order_finance_flow_model');
        return $this->_CI->order_finance_flow_model->clear($this->_Order['order_id']);
    }

    private function _clear_application () {
        $this->_CI->load->model('order/application_model');
        return $this->_CI->application_model->clear($this->_Order['order_id'], 'payterms');
    }
    private function _clear_order_product_classify () {
        $this->_CI->load->model('order/order_product_classify_model');
        return $this->_CI->order_product_classify_model->clear($this->_OrderProduct);
    }
    private function _clear_order_product_board () {
        $this->_CI->load->model('order/order_product_board_model');
        return $this->_CI->order_product_board_model->clear($this->_OrderProduct);
    }
    private function _clear_order_product_board_plate () {
        $this->_CI->load->model('order/order_product_board_plate_model');
        return $this->_CI->order_product_board_plate_model->clear($this->_OrderProduct);
    }
    /**
     * 重新拆单
     */
    public function re_dismantle() {
        $this->_Order['order_id'] = $this->_Workflow->get_source_ids();
        $this->_clear_application();
        $this->_Workflow->set_data(array(
            'payterms' => NORMAL_PAY
        ));
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['dismantling']);
        $this->_Workflow->re_dismantle();
    }
    
    /**
     * 重新核价
     */
    public function re_valuate() {
        $this->_Order['order_id'] = $this->_Workflow->get_source_ids();
        $this->_clear_application();
        $this->_Workflow->set_data(array(
            'payterms' => NORMAL_PAY
        ));
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['valuating']);
        $this->_Workflow->re_valuate();
    }

    public function pre_produce () {
        $this->_Workflow->edit_current_workflow(Workflow_order::$AllWorkflow['pre_produce']);
        $this->_Workflow->pre_produce();
    }

    public function __call($name, $arguments) {
        ;
    }
}
