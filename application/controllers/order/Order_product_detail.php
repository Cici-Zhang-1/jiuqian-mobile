<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月12日
 * @author Zhangcc
 * @version
 * @des
 */
class Order_product_detail extends CWDMS_Controller{
    private $Module = 'order';

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Order_product_detail Start !');
        $this->load->model('order/order_model');
    }

    public function index(){
        $View = $this->uri->segment(4, 'read');
        if(method_exists(__CLASS__, '_'.$View)){
            $View = '_'.$View;
            $this->$View();
        }else{
            $Item = $this->Module.'/'.strtolower(__CLASS__).'/'.$View;
            $this->data['action'] = site_url($Item);
            $this->load->view($Item, $this->data);
        }
    }
    
    private function _read(){
        $Id = $this->input->get('id', true);
        $Product = $this->input->get('product', true);
        $Id = intval(trim($Id));
        $Product = trim($Product);
        if($Id && $Product){
            $Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__.'_'.$Product;
            $this->load->view($Item, array('id' => $Id, 'product' => $Product));
        }else{
            show_404();
        }
    }

    public function read(){
        $Opid = $this->input->get('id');
        $Opid = intval(trim($Opid));
        $Product = $this->input->get('product', true);
        $Product = trim($Product);
        $Data = array();
        if($Opid && $Product){
            $Cache = $Opid.'_'.$Product.'_order_order_product_board_plate';
            $this->e_cache->open_cache();
            $Return = array();
            if(!($Return = $this->cache->get($Cache))){
                $Method = '_'.__FUNCTION__.'_'.$Product;
                if(method_exists(__CLASS__, $Method)){
                    $Return = $this->$Method($Opid);
                    $this->cache->save($Cache, $Return, HOURS);
                }else{
                    $this->Failue .= '您选择的订单没有板块详情!';
                }
            }
            $Data['content'] = $Return;
            unset($Return);
        }else{
            $this->Failue .= '您选择的订单不存在!';
        }
        $this->_return($Data);
    }

    private function _read_w($Opid){
        $Return = array();
        $this->load->model('order/order_product_board_plate_model');
        if(!!($Query = $this->order_product_board_plate_model->select_order_product_board_plate($Opid))){
            $this->config->load('dbview/order');
            $Dbview = $this->config->item('order/order_product_detail/_read_w');
            foreach ($Query as $key=>$value){
                foreach ($Dbview as $ikey=>$ivalue){
                    $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                }
                $Return[$key]['width'] = floatval($Return[$key]['width']);
                $Return[$key]['length'] = floatval($Return[$key]['length']);
            }
        }else{
            $this->Failue .= '该订单暂时没有板块信息';
        }
        return $Return;
    }
    
    private function _read_y($Opid){
        $Return = array();
        $this->load->model('order/order_product_board_plate_model');
        if(!!($Query = $this->order_product_board_plate_model->select_order_product_board_plate($Opid))){
            $this->config->load('dbview/order');
            $Dbview = $this->config->item('order/order_product_detail/_read_y');
            foreach ($Query as $key=>$value){
                foreach ($Dbview as $ikey=>$ivalue){
                    $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                }
                $Return[$key]['width'] = floatval($Return[$key]['width']);
                $Return[$key]['length'] = floatval($Return[$key]['length']);
            }
        }else{
            $this->Failue .= '该订单暂时没有板块信息';
        }
        return $Return;
    }

    private function _read_m($Opid){
        $Return = array();
        $this->load->model('order/order_product_door_plate_model');
        if(!!($Query = $this->order_product_door_plate_model->select_order_product_door_plate($Opid))){
            $this->config->load('dbview/order');
            $Dbview = $this->config->item('order/order_product_detail/_read_m');
            foreach ($Query as $key=>$value){
                foreach ($Dbview as $ikey=>$ivalue){
                    $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                }
                $Return[$key]['width'] = floatval($Return[$key]['width']);
                $Return[$key]['length'] = floatval($Return[$key]['length']);
            }
        }else{
            $this->Failue .= '该订单暂时没有板块信息';
        }
        return $Return;
    }
    /**
     * 木框门
     * @param unknown $Opid
     */
    private function _read_k($Opid){
        $Return = array();
        $this->load->model('order/order_product_door_plate_model');
        if(!!($Query = $this->order_product_door_plate_model->select_order_product_door_plate($Opid))){
            $this->config->load('dbview/order');
            $Dbview = $this->config->item('order/order_product_detail/_read_k');
            foreach ($Query as $key=>$value){
                foreach ($Dbview as $ikey=>$ivalue){
                    $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                }
                $Return[$key]['width'] = floatval($Return[$key]['width']);
                $Return[$key]['length'] = floatval($Return[$key]['length']);
            }
        }else{
            $this->Failue .= '该订单暂时没有板块信息';
        }
        return $Return;
    }

    /**
     * 配件
     * @param unknown $Opid
     */
    private function _read_p($Opid){
        $Return = array();
        $this->load->model('order/order_product_fitting_model');
        if(!!($Query = $this->order_product_fitting_model->select_order_product_fitting($Opid))){
            $this->config->load('dbview/order');
            $Dbview = $this->config->item('order/order_product_detail/_read_p');
            foreach ($Query as $key=>$value){
                foreach ($Dbview as $ikey=>$ivalue){
                    $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                }
            }
        }else{
            $this->Failue .= '该订单暂时没有配件信息';
        }
        return $Return;
    }

    /**
     * 外购
     * @param unknown $Opid
     */
    private function _read_g($Opid){
        $Return = array();
        $this->load->model('order/order_product_other_model');
        if(!!($Query = $this->order_product_other_model->select_order_product_other($Opid))){
            $this->config->load('dbview/order');
            $Dbview = $this->config->item('order/order_product_detail/_read_g');
            foreach ($Query as $key=>$value){
                foreach ($Dbview as $ikey=>$ivalue){
                    $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
                }
            }
        }else{
            $this->Failue .= '该订单暂时没有其他信息';
        }
        return $Return;
    }

    /**
     * 外购
     * @param unknown $Opid
     */
    private function _read_f($Opid){
        $Return = array();
        /* $this->load->model('order/order_product_server_model');
         if(!!($Query = $this->order_product_server_model->select_order_product_server_by_order_product($Opid))){
         $this->config->load('dbview/order');
         $Dbview = $this->config->item('order/order_detail/_read_f');
         foreach ($Query as $key=>$value){
         foreach ($Dbview as $ikey=>$ivalue){
         $Return[$key][$ivalue] = isset($value[$ikey])?$value[$ikey]:'';
         }
         }
        } */
        return $Return;
    }
}