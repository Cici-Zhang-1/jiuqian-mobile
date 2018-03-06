<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2016年3月2日
 * @author Administrator
 * @version
 * @des
 */
class Stock_outted_reprint extends CWDMS_Controller{
    private $_Module = 'stock';
    private $_Controller ;
    private $_Item ;
    private $_Cookie ;

    private $Search = array(
        'status' => '17,18,19,20,21',
        'keyword' => ''
    );
    public function __construct(){
        parent::__construct();
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = $this->_Module.'_'.$this->_Controller.'_';

        log_message('debug', 'Controller stock/Stock_outted_reprint Start!');
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
        $Id = $this->input->get('id', true);
        $Id = intval(trim($Id));
        $Item = $this->_Item.__FUNCTION__;
        if($Id > 0){
            $this->load->model('stock/stock_outted_model');
            $this->load->model('order/order_product_model');
            $Data = array();
            if(!!($Info = $this->stock_outted_model->select_by_id($Id)) 
                && !!($Query = $this->order_product_model->select_by_soid($Id, $this->Search['status']))){
                $Data['Logistics'] = 0; /**物流代收金额*/
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
                    if($value['pid'] != 7){
                        $OrderProductNum = explode('-', $value['order_product_num']);
                        $OrderProductNum = array_pop($OrderProductNum);
                        $Detail = sprintf('%s(%d)', $OrderProductNum, $value['order_product_pack']);
                        $Order[$value['did']]['detail'][$value['oid']][$value['pid']][] = $Detail;
                        $Order[$value['did']]['detail'][$value['oid']]['order_num'] = $value['order_num'];
                    }
                    $Selected[] = $value['oid'];
                }
                $Data['Selected'] = $Selected;
                $Data = array_merge($Data, $Info);
                unset($Selected);
                $Cookie = $this->_Cookie.'arrangement';
                $this->input->set_cookie(array('name' => $Cookie, 'value' => json_encode($Data), 'expire' => HOURS));
                $Data['Order'] = $Order;
                unset($Order);
                unset($Query);
                /* $this->input->set_cookie(array('name'=>'soid', 'value' => $Id, 'expire' => DAYS)); */
                $Data['Success'] = '获取重新打印成功!';
            }else{
                $Data['Failue'] = $GLOBALS['error'];
            }
        }else{
            $Data['Failue'] = '请您先选择要重新打印的发货记录!';
        }
        $this->load->view('header2');
        $this->load->view($this->_Item.__FUNCTION__, $Data);
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
}