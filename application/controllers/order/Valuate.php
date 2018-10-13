<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月15日
 * @author Administrator
 * @version
 * @des
 */
class Valuate extends MY_Controller{
    private $Search = array(
        'status' => array(
            O_VALUATE,
            O_VALUATING
        ),
        'keyword' => '',
        'all' => NO,
        'order_id' => ZERO
    );
    private $_OrderId;
    private $_OrderProductBoard = array();
    private $_OrderProduct = array();
    private $_SumDetail = array();  /*核价详情*/
    private $_SumDiff = 0;    /* 差价*/
    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Wait_asure Start!');
        $this->load->model('order/order_model');
    }

    public function read(){
        $this->_Search = array_merge($this->_Search, $this->Search);
        $this->get_page_search();
        if (empty($this->_Search['order_id'])) {
            $OrderId = $this->input->get('v', true);
            $this->_Search['order_id'] = intval($OrderId);
        }
        if (!empty($this->_Search['order_id'])) {
            $this->_Search['all'] = true;
        }
        $Data = array();
        if(!($Data = $this->order_model->select($this->_Search, '_valuate'))){
            $this->Message = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'读取信息失败';
            $this->Code = EXIT_ERROR;
        } else {
            foreach ($Data['content'] as $key => $value){
                $Tmp = json_decode($value['sum_detail'], true);
                if(is_array($Tmp)){
                    $Data['content'][$key] = array_merge($Data['content'][$key], $Tmp);
                } else {
                    $Data['content'][$key]['cabinet'] = '';
                    $Data['content'][$key]['wardrobe'] = '';
                    $Data['content'][$key]['door'] = '';
                    $Data['content'][$key]['wood'] = '';
                    $Data['content'][$key]['fitting'] = '';
                    $Data['content'][$key]['other'] = '';
                    $Data['content'][$key]['server'] = '';
                }
                unset($Data['content'][$key]['sum_detail']);
                unset($Tmp);
            }
        }
        if (!empty($this->_Search['order_id'])) {
            $Data['query']['order_id'] = $this->_Search['order_id'];
        }

        $this->_ajax_return($Data);
    }

    public function edit () {
        $Save = $this->input->post('save', true);
        if (empty($Save)) {
            $Save = 'valuating';
        }
        $this->_OrderId = $this->input->post('order_id', true);
        if (empty($this->_OrderId)) {
            $this->_OrderId = $this->input->post('v', true);
        }
        $this->_OrderId = intval($this->_OrderId);
        if (!empty($this->_OrderId)) {
            if (!!($OrderProduct = $this->order_model->is_status($this->_OrderId, array(O_VALUATE, O_VALUATING)))) {
                $this->_edit_cabinet();
                $this->_edit_wardrobe();
                $this->_edit_door();
                $this->_edit_wood();
                $this->_edit_order_product_board();

                $this->_edit_fitting();
                $this->_edit_other();
                $this->_edit_server();

                $this->_edit_order();
                $this->_edit_order_product();

                $this->_workflow($Save);
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '当前订单状态不能计价!';
            }
        } else {
            $this->Code = EXIT_ERROR;
            $this->Message = '请选择需要核价的订单!';
        }
        $this->_ajax_return();
    }

    private function _workflow ($Save) {
        $this->load->library('workflow/workflow');
        $W = $this->workflow->initialize('order');

        if(!!($W->initialize($this->_OrderId))){
            $W->{$Save}();
            $this->Message = '计价成功!';
            return true;
        }else{
            $this->Code = EXIT_ERROR;
            $this->Message = $W->get_failue();
            return false;
        }
    }

    private function _edit_order(){
        $Order = array(
            'sum' => array_sum($this->_SumDetail),
            'sum_detail' => json_encode($this->_SumDetail),
            'sum_diff' => ceil($this->_SumDiff)
        );
        $Order['sum'] = ceil($Order['sum']);
        $Order['virtual_sum'] = $Order['sum'] + $Order['sum_diff'];

        $this->order_model->update($Order, $this->_OrderId);
    }

    private function _edit_order_product(){
        if(count($this->_OrderProduct) > 0){
            $this->load->model('order/order_product_model');
            $this->order_product_model->update_batch($this->_OrderProduct);
        }
    }

    private function _edit_order_product_board(){
        if(count($this->_OrderProductBoard) > 0 ){   //板块
            $this->load->model('order/order_product_board_model');
            $this->_OrderProductBoard = gh_escape($this->_OrderProductBoard);
            $this->order_product_board_model->update_batch($this->_OrderProductBoard);
        }
    }

    private function _edit_cabinet(){
        $Cabinet = $this->input->post('valuate_cabinet_form', true);
        if($Cabinet){/*橱柜核价*/
            $this->_OrderProductBoard = array_merge($this->_OrderProductBoard, $Cabinet);
            $Sum = 0;
            $SumDiff = 0;
            foreach ($Cabinet as $key => $value){
                if(!isset($this->_OrderProduct[$value['order_product_id']])){
                    $this->_OrderProduct[$value['order_product_id']] = array(
                        'v' => $value['order_product_id'],
                        'sum' => $value['sum'],
                        'sum_diff' => $value['sum_diff']
                    );
                }else{
                    $this->_OrderProduct[$value['order_product_id']]['sum'] += $value['sum'];
                    $this->_OrderProduct[$value['order_product_id']]['sum_diff'] += $value['sum_diff'];
                }
                $this->_OrderProduct[$value['order_product_id']]['virtual_sum'] = $this->_OrderProduct[$value['order_product_id']]['sum'] + $this->_OrderProduct[$value['order_product_id']]['sum_diff'];
                $Sum += $value['sum'];
                $SumDiff += $value['sum_diff'];
            }
            $this->_SumDetail['cabinet'] = $Sum;
            $this->_SumDiff += $SumDiff;
        }else{
            $this->_SumDetail['cabinet'] = 0;
        }
    }

    private function _edit_wardrobe(){
        $Wardrobe = $this->input->post('valuate_wardrobe_form', true);
        if($Wardrobe){/*衣柜核价*/
            $this->_OrderProductBoard = array_merge($this->_OrderProductBoard, $Wardrobe);
            $Sum = 0;
            $SumDiff = 0;
            foreach ($Wardrobe as $key => $value){
                if(!isset($this->_OrderProduct[$value['order_product_id']])){
                    $this->_OrderProduct[$value['order_product_id']] = array(
                        'v' => $value['order_product_id'],
                        'sum' => $value['sum'],
                        'sum_diff' => $value['sum_diff']
                    );
                }else{
                    $this->_OrderProduct[$value['order_product_id']]['sum'] += $value['sum'];
                    $this->_OrderProduct[$value['order_product_id']]['sum_diff'] += $value['sum_diff'];
                }
                $this->_OrderProduct[$value['order_product_id']]['virtual_sum'] = $this->_OrderProduct[$value['order_product_id']]['sum'] + $this->_OrderProduct[$value['order_product_id']]['sum_diff'];
                $Sum += $value['sum'];
                $SumDiff += $value['sum_diff'];
            }
            $this->_SumDetail['wardrobe'] = $Sum;
            $this->_SumDiff += $SumDiff;
        }else{
            $this->_SumDetail['wardrobe'] = 0;
        }
    }

    private function _edit_door(){
        $Door = $this->input->post('valuate_door_form', true);
        if($Door){/*门板核价*/
            $this->_OrderProductBoard = array_merge($this->_OrderProductBoard, $Door);
            $Sum = 0;
            $SumDiff = 0;
            foreach ($Door as $key => $value){
                if(!isset($this->_OrderProduct[$value['order_product_id']])){
                    $this->_OrderProduct[$value['order_product_id']] = array(
                        'v' => $value['order_product_id'],
                        'sum' => $value['sum'],
                        'sum_diff' => $value['sum_diff']
                    );
                }else{
                    $this->_OrderProduct[$value['order_product_id']]['sum'] += $value['sum'];
                    $this->_OrderProduct[$value['order_product_id']]['sum_diff'] += $value['sum_diff'];
                }
                $this->_OrderProduct[$value['order_product_id']]['virtual_sum'] = $this->_OrderProduct[$value['order_product_id']]['sum'] + $this->_OrderProduct[$value['order_product_id']]['sum_diff'];
                $Sum += $value['sum'];
                $SumDiff += $value['sum_diff'];
            }
            $this->_SumDetail['door'] = $Sum;
            $this->_SumDiff += $SumDiff;
        }else{
            $this->_SumDetail['door'] = 0;
        }
    }

    private function _edit_wood(){
        $Wood = $this->input->post('valuate_wood_form', true);
        if($Wood){/*木框核价*/
            $this->_OrderProductBoard = array_merge($this->_OrderProductBoard, $Wood);
            $Sum = 0;
            $SumDiff = 0;
            foreach ($Wood as $key => $value){
                if(!isset($this->_OrderProduct[$value['order_product_id']])){
                    $this->_OrderProduct[$value['order_product_id']] = array(
                        'v' => $value['order_product_id'],
                        'sum' => $value['sum'],
                        'sum_diff' => $value['sum_diff']
                    );
                }else{
                    $this->_OrderProduct[$value['order_product_id']]['sum'] += $value['sum'];
                    $this->_OrderProduct[$value['order_product_id']]['sum_diff'] += $value['sum_diff'];
                }
                $this->_OrderProduct[$value['order_product_id']]['virtual_sum'] = $this->_OrderProduct[$value['order_product_id']]['sum'] + $this->_OrderProduct[$value['order_product_id']]['sum_diff'];
                $Sum += $value['sum'];
                $SumDiff += $value['sum_diff'];
            }
            $this->_SumDetail['wood'] = $Sum;
            $this->_SumDiff += $SumDiff;
        }else{
            $this->_SumDetail['wood'] = 0;
        }
    }

    private function _edit_fitting(){
        $Fitting = $this->input->post('valuate_fitting_form', true);
        if($Fitting){      //配件
            $Sum = 0;
            foreach ($Fitting as $key => $value){
                if(!isset($this->_OrderProduct[$value['order_product_id']])){
                    $this->_OrderProduct[$value['order_product_id']] = array(
                        'v' => $value['order_product_id'],
                        'sum' => $value['sum']
                    );
                }else{
                    $this->_OrderProduct[$value['order_product_id']]['sum'] += $value['sum'];
                }
                $Sum += $value['sum'];
            }
            $this->load->model('order/order_product_fitting_model');
            $Fitting = gh_escape($Fitting);
            $this->order_product_fitting_model->update_batch($Fitting);
            $this->_SumDetail['fitting'] = $Sum;
        }else{
            $this->_SumDetail['fitting'] = 0;
        }
    }

    private function _edit_other(){
        $Other = $this->input->post('valuate_other_form', true);
        if($Other){       //外购
            $Sum = 0;
            foreach ($Other as $key => $value){
                if(!isset($this->_OrderProduct[$value['order_product_id']])){
                    $this->_OrderProduct[$value['order_product_id']] = array(
                        'v' => $value['order_product_id'],
                        'sum' => $value['sum']
                    );
                }else{
                    $this->_OrderProduct[$value['order_product_id']]['sum'] += $value['sum'];
                }
                $Sum += $value['sum'];
            }
            $this->load->model('order/order_product_other_model');
            $Other = gh_escape($Other);
            $this->order_product_other_model->update_batch($Other);
            $this->_SumDetail['other'] = $Sum;
        }else{
            $this->_SumDetail['other'] = 0;
        }
    }

    private function _edit_server(){
        $Server = $this->input->post('valuate_server_form', true);
        if($Server){       //服务
            $Sum = 0;
            foreach ($Server as $key => $value){
                if(!isset($this->_OrderProduct[$value['order_product_id']])){
                    $this->_OrderProduct[$value['order_product_id']] = array(
                        'v' => $value['order_product_id'],
                        'sum' => $value['sum']
                    );
                }else{
                    $this->_OrderProduct[$value['order_product_id']]['sum'] += $value['sum'];
                }
                $Sum += $value['sum'];
            }
            $this->load->model('order/order_product_server_model');
            $Server = gh_escape($Server);
            $this->order_product_server_model->update_batch($Server);
            $this->_SumDetail['server'] = $Sum;
        }else{
            $this->_SumDetail['server'] = 0;
        }
    }

    /**
     * 核价完成
     */
    public function valuated () {
        $V = $this->input->post('v', true);
        $_POST['v'] = is_array($V) ? $V : explode(',', $V);
        unset($V);
        if ($this->_do_form_validation()) {
            $Where = $this->input->post('v', true);
            if (!!($Order = $this->order_model->are_status($Where, array(O_VALUATE, O_VALUATING)))) {
                foreach ($Order as $Key => $Value) {
                    $this->_OrderId = $Value['v'];
                    if (!($this->_workflow('valuated'))) {
                        break;
                    }
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '没有可以确认核价的订单!';
            }
        }
        $this->_ajax_return();
    }

    /**
     * 重新核价
     */
    public function re_valuate () {
        if ($this->_do_form_validation()) {
            $V = $this->input->post('v', true);
            if (!!($this->order_model->is_status($V, array(O_CHECK, O_CHECKED, O_WAIT_SURE, O_PRODUCE)))) {
                $this->load->library('workflow/workflow');
                $W = $this->workflow->initialize('order');
                if ($W->initialize($V)) {
                    $W->re_valuate();
                    $this->Message = '订单重新核价成功!';
                    $this->Location = '/order/valuate?order_id=' . $V;
                } else {
                    $this->Code = EXIT_ERROR;
                    $this->Message = $W->get_failue();
                }
            } else {
                $this->Code = EXIT_ERROR;
                $this->Message = '这个订单不能已经不能重新核价!';
            }
        }
        $this->_ajax_return();
    }
}
