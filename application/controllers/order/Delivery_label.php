<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年12月18日
 * @author Zhangcc
 * @version
 * @des
 */
class Delivery_label extends MY_Controller{
    private $_Module = 'order';
    private $_Controller;
    private $_Item;
    private $_Cookie;
    private $_Type;
    private $_Id;
    private $_Code;

    public function __construct(){
        parent::__construct();
        $this->_Controller = strtolower(__CLASS__);
        $this->_Item = $this->_Module.'/'.$this->_Controller.'/';
        $this->_Cookie = str_replace('/', '_', $this->_Item).'_';
        
        $this->load->model('order/order_model');
        
        log_message('debug', 'Controller Order/Delivery_label Start !');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->_Item.$View;
            $Data['action'] = site_url($Item);
            $this->load->view('header2');
            $this->load->view($Item, $Data);
        }
    }

    public function read(){
        $Year = $this->input->get('year', true);
        $Month = $this->input->get('month', true);
        $Prefix = $this->input->get('prefix', true);
        $Type = $this->input->get('type', true); /*正常单、增补单*/
        $Year = intval(trim($Year));
        $Month = intval(trim($Month));
        $Type = strtoupper(trim($Type));
        
        $Data = array();
        if(preg_match('/^[\d]{4,4}$/', $Year) && preg_match('/^[\d]{1,2}$/', $Month)
            && preg_match('/^[X]|[B]$/', $Type) && preg_match('/^[\d]{1,4}$/', $Prefix)){
            $OrderNum = sprintf('%s%d%02d%04d',$Type,$Year,$Month,$Prefix);
            if(!!($Data = $this->order_model->is_deliveriable($OrderNum))){
                $this->Success = '成功获得要打印标签的订单';
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的清单';
            }
        }else{
            $this->Failue = '您提供的信息不正确';
        }
        $this->_return($Data);
    }
    
    private function _print(){
        $OrderNum = $this->input->get('order_num', true);
        $OrderNum = trim($OrderNum);
        $Data = array();
        $Length = ORDER_PREFIX + ORDER_SUFFIX;
        if(preg_match("/^(X|B)[\d]{{$Length},{$Length}}$/", $OrderNum)){
            $Item = $this->_Item.__FUNCTION__;
            $this->load->model('order/order_product_model');
            if(!!($Query = $this->order_product_model->is_deliveriable($OrderNum))){
                foreach ($Query as $key => $value){
                    $Tmp = json_decode($value['order_pack_detail'], true);
                    if(is_array($Tmp)){
                        $value = array_merge($value, $Tmp);
                    }
                    $Query[$key] = $value;
                }
                $Data['Data'] = $Query;
                $this->load->view('header2');
                $this->load->view($Item,$Data);
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的发货标签';
                gh_alert_back($this->Failue);
            }
        }else{
            gh_alert_back('您要打印的发货标签内容不存在');
        }
    }
    
    public function edit(){
        $Id = $this->input->post('id', true);
        $Id = intval(trim($Id));
        $Pack = $this->input->post('pack', true);
        $Pack = intval(trim($Pack));
        $this->load->helper('cookie');
        $Cookie = $this->_Cookie.'classify';
        $Classify = $this->input->cookie($Cookie);
        delete_cookie($Cookie);
        $Cookie = $this->_Cookie.'pack_detail';
        $PackDetail = $this->input->cookie($Cookie); 
        $PackDetail = json_decode($PackDetail, true);
        delete_cookie($Cookie);
        unset($Cookie);
        
        if('both' == $Classify){
            unset($PackDetail);
        }
        $PackDetail[$Classify] = $Pack;
        
        if(in_array(0, $PackDetail)){
            $Keep = true;
        }else{
            $Keep = false;
        }
        if($Id > 0){
            $Set = array(
                'pack' => array_sum($PackDetail),
                'pack_detail' => json_encode($PackDetail),
                'packer' => $this->session->userdata('uid'),
                'pack_datetime' => date('Y-m-d H:i:s')
            );
            if(!!($this->order_product_model->update($Set, $Id))){
                $this->load->library('workflow/workflow');
                if($this->workflow->initialize('order_product', $Id)){
                    if($Keep){
                        $this->workflow->pack();
                    }else{
                        $this->workflow->packed();
                    }
                    $this->Success = '包装标签打印保存成功!';
                }else{
                    $this->Failue = $this->workflow->get_failue();
                }
            }else{
                $this->Failue .= isset($GLOBALS['error'])?is_array($GLOBALS['error'])?implode(',', $GLOBALS['error']):$GLOBALS['error']:'没有需要打印的清单';
            }
        }else{
            $this->Failue = '您要保存的包装标签不正确, 请检查!';
        }
        $this->_return();
    }
}
