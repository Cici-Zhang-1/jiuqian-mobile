<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 2015年11月6日
 * @author Zhangcc
 * @version
 * @des
 * 获取相关图纸
 */
class Order_drawing extends CWDMS_Controller{
    private $Module = 'order';

    public function __construct(){
        parent::__construct();
        log_message('debug', 'Controller Order/Order_drawing Start !');
        $this->load->model('order/order_drawing_model');
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
        $Id = intval(trim($Id));
        if($Id){
            $Item = $this->Module.'/'.strtolower(__CLASS__).'/'.__FUNCTION__;
            $this->load->view($Item, array('id' => $Id));
        }else{
            show_404();
        }
    }

    public function read(){
        $Opid = $this->input->get('id', true);
        $Opid = intval(trim($Opid));
        $Data = array();
        if($Opid){
            $Cache = $Opid.'_order_order_drawing';
            $this->e_cache->open_cache();
            $Return = array();
            if(!($Return = $this->cache->get($Cache))){
                if(!!($Query = $this->order_drawing_model->select_order_drawing($Opid))){
                    $this->config->load('dbview/order');
                    $Dbview = $this->config->item('order/order_drawing/read');
                    foreach ($Dbview as $ikey=>$ivalue){
                        $Query[$ikey] = json_decode($Query[$ikey], true);
                        if(is_array($Query[$ikey])){
                            foreach ($Query[$ikey] as $iikey => $iivalue){
                                $Tmp[$iikey] = base_url(substr($iivalue, strlen(ROOTDIR)));
                            }
                            $Return[$ivalue] = $Tmp;
                        }
                    }
                    $this->cache->save($Cache, $Return, HOURS);
                }else{
                    $this->Failue .= '该订单暂时图纸信息';
                }
            }
            $Data['content'] = $Return;
            unset($Return);
        }else{
            $this->Failue .= '您选择的订单不存在!';
        }
        $this->_return($Data);
    }
}