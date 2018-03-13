<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年1月18日
 * @author Zhangcc
 * @version
 * @des
 * 等待发货
 */
class Wait_delivery extends MY_Controller{
    private $_Module = 'order';
    private $_Controller ;
    private $_Item ;
    private $_Cookie ;
    private $_Out = 0;
    private $_Unfull = 1;
    private $_Empty = 0;
    private $Search = array(
        'status' => '16',
        'out_method' => ''
    );
    
    public function __construct(){
        parent::__construct();
        $this->load->model('order/order_model');
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';
        
        log_message('debug', 'Controller Order/Wait_delivery Start!');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view($Item, $Data);
        }
    }

    private function _read(){
        $Data = array();
        $this->load->model('data/out_method_model');
        if(!!($Tab = $this->out_method_model->select())){
            $Tab = $Tab['content'];
            foreach ($Tab as $key => $value){
                $Method = __FUNCTION__.'_'.$value['omid'];
                $this->Search['out_method'] = $value['name'];
                $Data['Outting'][$value['omid']]['content'] =  $this->$Method();
                $Data['Outting'][$value['omid']] =  array_merge($Data['Outting'][$value['omid']], $value);
            }
        }else{
            $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有出厂方式!';
        }
        if('' == $this->Failue){
            $this->load->view($this->_Item.__FUNCTION__, $Data);
        }else{
            show_error($this->Failue);
        }
    }

    /**
     * 物流到厂
     */
    private function _read_3(){
        $Return = false;
        $Data = array();
        if(!!($Data['Order'] = $this->order_model->select_wait_delivery($this->Search))){
            $Return = $this->load->view($this->_Item.__FUNCTION__, $Data, TRUE);
        }else{
            $Return = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'暂时没有物流到厂订单!';
        }
        return $Return;
    }

    private function _read_2(){
        $Return = false;
        $Data = array();
        if(!!($Data['Order'] = $this->order_model->select_wait_delivery($this->Search))){
            $Return = $this->load->view($this->_Item.__FUNCTION__, $Data, TRUE);
        }else{
            $Return = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'暂时没有发货物流订单!';
        }
        return $Return;
    }

    private function _read_1(){
        $Return = false;
        $Data = array();
        if(!!($Data['Order'] = $this->order_model->select_wait_delivery($this->Search))){
            $Return = $this->load->view($this->_Item.__FUNCTION__, $Data, TRUE);
        }else{
            $Return = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'暂时没有到厂自提订单!';
        }
        return $Return;
    }

    /**
     * 拟定发货
     */
    private function _protocol(){
        $Data = array();
        $Data['Amount'] = $this->input->get('amount', true);
        $Data['Truck'] = $this->input->get('truck', true);
        $Data['Train'] = $this->input->get('train', true);
        $Data['EndDatetime'] = $this->input->get('end_datetime', true);
        $Data['Selected'] = $this->input->get('selected', true);
        $Data['Amount'] = intval(trim($Data['Amount']));
        if($Data['Amount'] <= 0 || empty($Data['Selected']) || !is_array($Data['Selected'])){
            show_error('请选择需要发货的订单');
            exit();
        }
        $Data['EndDatetime'] = trim($Data['EndDatetime']);
        if(empty($Data['EndDatetime'])){
            show_error('请选择发货日期');
            exit();
        }
        foreach ($Data['Selected'] as $key => $value){
            $Data['Selected'][$key] = intval(trim($value));
        }
        $this->load->model('order/order_product_model');
        if(!!($Query = $this->order_product_model->select_wait_delivery_by_ids($Data['Selected'], $this->Search['status']))){
            $Data['Logistics'] = 0; /**物流代收金额*/

            $Positions = $this->_read_positions($Data['Selected']);
            $Order = array();
            $Selected = array();
            foreach ($Query as $key => $value){
                if('物流代收' == $value['payed']){
                    $Payed = $value['sum'];
                }else{
                    $Payed = 0;
                }
                if(empty($Order[$value['did']])){
                    $Data['Logistics'] += $Payed;
                    $Order[$value['did']] = array(
                        'dealer' => $value['dealer'],
                        'delivery_address' => $value['delivery_area'].$value['delivery_address'],
                        'delivery_linker' => $value['delivery_linker'],
                        'delivery_phone' => $value['delivery_phone'],
                        'logistics' => $value['logistics'],
                        'amount' => $value['pack'],
                        'payed' => $Payed,
                        'detail' => array()
                    );
                }else{
                    if(empty($Order[$value['did']]['detail'][$value['oid']])){
                        $Order[$value['did']]['amount'] += $value['pack'];
                        $Order[$value['did']]['payed'] += $Payed;
                        $Data['Logistics'] += $Payed;
                    }
                }
                if($value['pid'] != 7){ /*计算需要打包的订单小编号*/
                    $OrderProductNum = explode('-', $value['order_product_num']);
                    $OrderProductNum = array_pop($OrderProductNum);
		            $OrderProductPackDetail = json_decode($value['order_product_pack_detail'], true);
                    $OrderProductPackDetailString = '';
                    foreach ($OrderProductPackDetail as $iikey => $iivalue) {
                        if ($iivalue > 0) {
                            if ('thick' == $iikey) {
                                $OrderProductPackDetailString .= 'H:' . $iivalue . '&nbsp;&nbsp;';
                            }elseif ('thin' == $iikey) {
                                $OrderProductPackDetailString .= 'B:' . $iivalue;
                            }
                        }
                    }
                    if (isset($Positions[$value['opid']])) {
                        $Detail = sprintf('%s(%d,%s)%s', $OrderProductNum, $value['order_product_pack'], $OrderProductPackDetailString, $Positions[$value['opid']]);
                    }else {
                        $Detail = sprintf('%s(%d,%s)', $OrderProductNum, $value['order_product_pack'], $OrderProductPackDetailString);
                    }
                    $Order[$value['did']]['detail'][$value['oid']][$value['pid']][] = $Detail;
                    $Order[$value['did']]['detail'][$value['oid']]['order_num'] = $value['order_num'];
                    $Order[$value['did']]['detail'][$value['oid']]['owner'] = $value['owner'];
                }
                $Selected[] = $value['oid'];
            }
            $Data['Selected'] = $Selected;
            unset($Selected);
            $Cookie = $this->_Cookie.'arrangement';
            $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($Data), 'expire' => HOURS));
            $Data['Order'] = $Order;
            unset($Order);
            unset($Query);
            $this->load->view('header2');
            $this->load->view($this->_Item.__FUNCTION__, $Data);
        }else{
            $this->Failue = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您选择的要发货的订单不存在!';
            show_error($this->Failue);
        }
    }

    /**
     * @param $Oids
     * @return bool
     * 库位
     */
    private function _read_positions($Oids) {
        $this->load->model('position/position_order_product_model');
        $Positions = false;
        if (!!($Query = $this->position_order_product_model->select_position_by_oid($Oids))) {
            foreach ($Query as $key=>$value) {
                $Positions[$value['opid']] = $value['name'];
            }
        }
        return $Positions;
    }

    /**
     * 打印发货标签
     */
    private function _print(){
        $Cookie = $this->_Cookie.'arrangement';
        $Data = $this->input->cookie($Cookie);
        $Data = json_decode($Data, true);
        foreach ($Data['Selected'] as $key => $value){
            $Data['Selected'][$key] = intval(trim($value));
        }
        if(is_array($Data) && count($Data) > 0){
            $this->load->model('order/order_product_model');
            if(!!($Query = $this->order_product_model->select_wait_delivery_by_ids($Data['Selected'], $this->Search['status']))){
                $Selected = array();
                foreach ($Query as $key => $value){
                    $Query[$key]['delivery_address'] = $value['delivery_area'].$value['delivery_address'];
                    $Tmp = explode('_', $value['dealer']);
                    $Query[$key]['dealer'] = $Tmp[1];
                }
                $Data['Order'] = $Query;
                $this->load->view('header2');
                $this->load->view($this->_Item.__FUNCTION__, $Data);
            }else{
                $this->Failue = isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'您选择的要发货的订单不存在!';
                show_error($this->Failue);
            }
        }else{
            show_error('您之前的安排已经过时, 请重新安排!');
        }
    }

    /**
     * 发货单打印，则确认为已发货
     */
    public function edit(){
        $Cookie = $this->_Cookie.'arrangement';
        $Data = $this->input->cookie($Cookie);
        $Data = json_decode($Data, true);
        $Selected = array();
        foreach ($Data['Selected'] as $key => $value){
            $Selected[] = intval(trim($value));
        }
        $Set['amount'] = $Data['Amount'];
        $Set['truck'] = $Data['Truck'];
        $Set['train'] = $Data['Train'];
        $Set['end_datetime'] = $Data['EndDatetime'];
        $Set['logistics'] = $Data['Logistics'];
        $Set['selected'] = implode(',', $Selected);
        unset($Data);
        
        if(is_array($Set) && count($Set) > 0){
            $this->load->model('stock/stock_outted_model');
            if(!!($Id = $this->stock_outted_model->insert($Set))){
                $this->Success = '发货标签打印保存成功!';
                $Set = array(
                    'end_datetime' => $Set['end_datetime'],
                    'stock_outted_id' => $Id
                );
                $this->order_model->update_order($Set, $Selected);

                $this->_edit_position($Selected);
                
                if(!!($Query = $this->order_model->select_wait_delivery_by_ids($Selected, $this->Search['status']))){
                    $Payed = array();
                    $MoneyLogistics = array();
                    $Dealer = array();
                    foreach ($Query as $key => $value){
                        if('已付' == $value['payed']){
                            $Payed[] = $value['oid'];
                        }elseif ('物流代收' == $value['payed']){
                            $MoneyLogistics[] = $value['oid'];
                        }elseif ('按月结款' == $value['payed']){
                            $MoneyMonth[] = $value['oid'];
                        }elseif ('到厂付款' == $value['payed']){
                            $MoneyFactory[] = $value['oid'];
                        }
                        if(empty($Dealer[$value['did']])){
                            $Dealer[$value['did']] = $value['sum'];
                        }else{
                            $Dealer[$value['did']] += $value['sum'];
                        }
                    }
                    unset($Query);
                    if(!empty($Dealer)){
                        $this->load->model('dealer/dealer_model');
                        $this->dealer_model->update_dealer_deliveried($Dealer);
                    }
                    
                    $this->load->library('workflow/workflow');
                    if(!empty($Payed)){
                        if($this->workflow->initialize('order', $Payed)){
                            $this->workflow->deliveried();
                        }else{
                            $this->Failue .= $this->workflow->get_failue();
                        }
                    }
                    if(!empty($MoneyLogistics)){
                        if($this->workflow->initialize('order', $MoneyLogistics)){
                            $this->workflow->money_logistics();
                        }else{
                            $this->Failue .= $this->workflow->get_failue();
                        }
                    }
                    if(!empty($MoneyMonth)){
                        if($this->workflow->initialize('order', $MoneyMonth)){
                            $this->workflow->money_month();
                        }else{
                            $this->Failue .= $this->workflow->get_failue();
                        }
                    }
                    if(!empty($MoneyFactory)){
                        if($this->workflow->initialize('order', $MoneyFactory)){
                            $this->workflow->money_factory();
                        }else{
                            $this->Failue .= $this->workflow->get_failue();
                        }
                    }
                }
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的清单';
            }
        }else{
            $this->Failue = '您要保存的包装标签不正确, 请检查!';
        }
        $this->_return();
    }

    private function _edit_position($Oids) {
        $this->load->model('order/order_product_model');
        if (!!($Query = $this->order_product_model->select_by_oid($Oids))) {
            $Opids = array();
            $Pids = array();
            $Unfull = array();
            $Empty = array();
            foreach ($Query as $key=>$value) {
                $Opids[] = $value['opid'];
            }
            $this->load->model('position/position_order_product_model');
            if (!!($Query = $this->position_order_product_model->select_pid_by_opid($Opids))) {
                /**
                 * 是否存在Pid
                 */
                foreach ($Query as $key => $value) {
                    $Pids[] = $value['pid'];
                }
                $Set = array(
                    'status' => $this->_Out,
                    'destroy' => $this->session->userdata('uid'),
                    'destroy_datetime' => date('Y-m-d H:i:s')
                );
                $this->position_order_product_model->update_after_out($Set, $Opids);

                $this->load->model('position/position_model');
                if (!!($Query = $this->position_order_product_model->select_unfull_pid($Pids))) {
                    foreach ($Query as $key => $value) {
                        $Unfull[] = $value['pid'];
                    }
                    $Set = array('status' => $this->_Unfull);
                    $this->position_model->update_position($Set, $Unfull);
                }
                $Empty = array_diff($Pids, $Unfull);
                $Set = array('status' => $this->_Empty);
                if (count($Empty) > 0) {
                    $this->position_model->update_position($Set, $Empty);
                }
            }

            return true;
        }
        return false;
    }
}
